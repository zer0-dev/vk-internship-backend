<?php
namespace Task;

class Config{
    public static $db_cred = ['host' => 'mysql:3306', 'user' => 'root', 'password' => 'secret', 'db' => 'task'];
    public static $jwt_secret = 'SUPER_SECRET';
}