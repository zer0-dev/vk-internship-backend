<?php
namespace Task\Model;

use Task\Model\Enums\PasswordStrength;

class Password implements \JsonSerializable{
    private string $password;
    private PasswordStrength $strength;

    public function __construct(string $password){
        $this->password = $password;
        $this->strength = Password::determineStrength($password);
    }

    public function getPassword(): string{
        return $this->password;
    }

    public function getStrength(): PasswordStrength{
        return $this->strength;
    }

    public function setPassword(string $password): void{
        $this->password = $password;
    }

    public function setStrength(PasswordStrength $strength): void{
        $this->strength = $strength;
    }

    public function jsonSerialize(): mixed{
        return get_object_vars($this);
    }

    public static function determineStrength(string $password): PasswordStrength{
        if(strlen($password) >= 8 && preg_match("/[a-z]/", $password) && preg_match("/\d/", $password) && strlen($password) <= 255){
            if(strlen($password) >= 12 && preg_match("/[A-Z]/", $password)){
                return PasswordStrength::Perfect;
            } else{
                return PasswordStrength::Good;
            }
        } else {
            return PasswordStrength::Weak;
        }
    }
}