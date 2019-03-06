<?php

namespace app\common\lib;

use app\common\lib\Aes;
use app\common\lib\exception\ApiException;
use think\Cache;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

 /**
  * 
  */
 class IAuth 
 {
    //密码加密
    public static function setPassword($password)
    {
        return md5($password.config('app.password_halt'));
    }

    /**
     * 生成每次请求的sign
     * @param array $data [description]
     */
    public static function setSign($data = [])
    {
        // 1. 按字段排序
        ksort($data);
        // 2. 字符串拼接
        $string = http_build_query($data);
        // 3. 通过aes来加密
        $string = (new Aes())->encrypt($string);
        // 4. 将所有字符串转化大写
        $string = strtoupper($string);

        return $string;
    }

    /**
     * 检查sign是否正常
     * @param  string $sign [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function checkSignPass($data)
    {
        try {
            $str = (new Aes())->decrypt($data['sign']);
        } catch (\Exception $e) {
            throw new ApiException('授权码解析失败', 401);
        }

        if (empty($str)) {
            return false;
        }

        //将字符串转成数组
        parse_str($str, $arr);
        if (!is_array($arr) || empty($arr['name']) || $arr['time'] != $data['time']) {
            return false;
        }

        if ((time() - ceil($arr['time']/1000)) > config('app.app_sign_time')) {
            return false;
        }
        //唯一性判定
        if (Cache::get($data['sign'])) {
            return false;
        }
        return true;
    }
 }