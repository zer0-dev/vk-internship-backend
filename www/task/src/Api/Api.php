<?php
namespace Task\Api;

use Task\Config;
use Task\Database\Database;
use Task\Model\User;
use Task\Model\Error;
use Task\Model\Validator;
use Task\Model\Password;
use Task\Model\Enums\PasswordStrength;
use Task\Auth\JWT;

class Api{
    private static Database $db;

    public static function init(): void {
      Api::$db = new Database(Config::$db_cred);
    }

    public static function handleRequest(string $path, array $data): mixed{
        switch($path){
            case "/register":
                if(empty($data['email']) || empty($data['password'])) return new Error(2, "No data passed");
                $email = $data['email'];
                $password = new Password($data['password']);
                if(Validator::validateEmail($email)){
                    if($password->getStrength() != PasswordStrength::Weak){
                        return Api::$db->addUser(new User(-1, $email, $password));
                    } else{
                        return new Error(2, "Password is too weak");
                    }
                } else{
                    return new Error(2, "E-mail is not valid");
                }
            case "/authorize":
                if(empty($data['email']) || empty($data['password'])) return new Error(2, "No data passed");
                $email = $data['email'];
                $password = $data['password'];
                $userRes = Api::$db->getUserByEmail($email, $password);
                if(gettype($userRes) == "array"){
                    $jwt = new JWT($userRes);
                    return ['access_token' => $jwt->encode()];
                } else {
                    return $userRes;
                }
            case "/feed":
                if(empty($data['access_token'])){
                    header("HTTP/1.1 401 Unauthorized");
                    return null;
                }
                $jwt = JWT::decode($data['access_token']);
                if($jwt){
                    $userRes = Api::$db->getUserById($jwt->getPayload()['user_id']);
                    if(gettype($userRes) == "object"){
                        header("HTTP/1.1 401 Unauthorized");
                    }
                } else{
                    header("HTTP/1.1 401 Unauthorized");
                }
                return null;
            default:
                header("HTTP/1.1 404 Not Found");
                return null;
        }
    }
}