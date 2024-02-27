<?php namespace common\extend;

use Exception;

class MyException extends Exception
{
    public $errorCode;
    public function __construct($arr)
    {
        $code = isset($arr[0]) ? $arr[0] : "";
        $msg = isset($arr[1]) ? $arr[1] : "";
        parent::__construct($msg);
        $this->errorCode = $code;
    }
}

?>
