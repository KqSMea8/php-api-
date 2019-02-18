<?php

namespace app\common\lib;

use app\common\lib\Aes;
use app\common\lib\exception\ApiException;
 /**
  * 
  */
 class Time 
 {
    /**获取13位时间戳
     * [get13TimeStamp description]
     * @return [type] [description]
     */
    public static function get13TimeStamp()
    {
        list($t1, $t2) = explode(' ', microtime());
        return $t2 . ceil($t1*1000);
    }
 }