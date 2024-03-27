<?php
namespace Task\Model;
/* error codes:
1 - internal database error
2 - bad request */

class Error implements \JsonSerializable{

    private int $code;
    private string $msg;

    public function __construct(int $code, string $msg){
        $this->code = $code;
        $this->msg = $msg;
    }

    public function getCode(): int{
        return $this->code;
    }

    public function getMsg(): string{
        return $this->msg;
    }

    public function setCode(int $code): void{
        $this->code = $code;
    }

    public function setMsg(string $msg): void{
        $this->msg = $msg;
    }

    public function jsonSerialize(): mixed{
        return get_object_vars($this);
    }
}