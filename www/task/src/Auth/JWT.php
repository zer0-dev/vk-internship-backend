<?php
namespace Task\Auth;
use Task\Config;

class JWT{
    private array $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    private array $payload;

    public function __construct(array $payload){
        $this->payload = $payload;
    }

    public function getPayload(): array{
        return $this->payload;
    }

    public function setPayload(array $payload): void{
        $this->payload = $payload;
    }

    public function encode(): string{
        $header = base64_encode(json_encode(($this->header)));
        $payload = base64_encode(json_encode(($this->payload)));
        $signature = hash_hmac('sha256', $header.".".$payload, Config::$jwt_secret);
        return $header.".".$payload.".".$signature;
    }

    public static function decode(string $token): mixed{
        $exp_token = explode(".", $token);
        $header = $exp_token[0];
        $payload = $exp_token[1];
        $signature = $exp_token[2];
        if(hash_hmac('sha256', $header.".".$payload, Config::$jwt_secret) == $signature){
            return new JWT(json_decode(base64_decode($payload), true));
        } else {
            return null;
        }
    }
}