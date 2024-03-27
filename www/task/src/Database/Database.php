<?php
namespace Task\Database;
use Task\Model\Password;
use Task\Model\User;
use Task\Model\Error;
use mysqli;
use Task\Model\Validator;

class Database{
    private mysqli $db;
    public function __construct(array $db_cred){
        $this->db = new mysqli($db_cred['host'], $db_cred['user'], $db_cred['password'], $db_cred['db']);
    }

    public function addUser(User $user): mixed{
        $email = $user->getEmail();
        $password = $user->getPassword()->getPassword();

        $prep = $this->db->prepare("SELECT count(*) as c FROM users WHERE email = ?");
        $prep->bind_param('s', $email);
		$prep->execute();
		$count = $prep->get_result()->fetch_assoc()['c'];
        if($count == 0){
            $prep = $this->db->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $prep->bind_param('ss', $email, $password);
            $prep->execute();
            
            if(!empty($this->db->error)){
                return new Error(1, $this->db->error);
            }
            $user->setId($this->db->insert_id);
            
            return ['user_id' => $user->getId(), 'password_check_status' => $user->getPassword()->getStrength()];
        } else {
            return new Error(2, "User with this e-mail already exists");
        }
    }

    public function getUserByEmail(string $email, string $password): mixed{
        $prep = $this->db->prepare("SELECT id FROM users WHERE email = ? AND password = ?");
        $prep->bind_param('ss', $email, $password);
        $prep->execute();
		$res = $prep->get_result();
        if($res->num_rows > 0){
            $u = $res->fetch_assoc();
            return ['user_id' => $u['id']];
        } else{
            return new Error(2, "Incorrect e-mail or password");
        }
    }

    public function getUserById(int $id): mixed{
        $prep = $this->db->prepare("SELECT id FROM users WHERE id = ?");
        $prep->bind_param('i', $id);
        $prep->execute();
		$res = $prep->get_result();
        if($res->num_rows > 0){
            $u = $res->fetch_assoc();
            return ['user_id' => $u['id']];
        } else{
            return new Error(2, "User not found");
        }
    }
}