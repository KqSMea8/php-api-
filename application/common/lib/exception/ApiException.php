<?php
namespace app\common\lib\exception;

/**
 * 
 */
use think\Exception;

class ApiException extends Exception
{
    /**
     * http 状态码
     * @var integer
     */
    public $httpCode = 500;
    public $message = '';
    public $code = 0;

    public function __construct($message = '', $httpCode = 0, $code = 0)
    {
        $this->httpCode = $httpCode;
        $this->message = $message;
        $this->code = $code;
    }
}