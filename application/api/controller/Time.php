<?php
namespace app\api\controller;

use think\Controller;
use think\Request;
use app\common\lib\exception\ApiException;
use app\api\controller\Common;
/**
 * 
 */
class Time extends Controller
{
    public function index()
    {
        return show(1, 'ok', time());
    }
}
