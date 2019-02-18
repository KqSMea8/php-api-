<?php
namespace app\common\lib\exception;

/**
 * 
 */
use think\exception\Handle;
use Exception;

/**
 * 框架异常处理
 */
class ApiHandleException extends Handle
{
    /**
     * http 状态码
     * @var integer
     */
    public $httpCode = 500;

    public function render(Exception $e)
    {
        //开发模式显示异常问题
        if (config('app_debug') == true) {
            return parent::render($e);
        }

        //异常处理返回接口
        if ($e instanceof ApiException) {
            $this->httpCode = $e->httpCode;
        }

        return show(0, $e->getMessage(), [], $this->httpCode);

        // $data = [
        //     'status' => 0,
        //     'message' => $e->getMessage(),
        //     'data' => [],
        // ];
        // return josn($data, 400);
    }
}