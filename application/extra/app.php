<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

return [
    'password_halt' => '_shidun_',//密码加密的盐
    'aeskey' => 'shidun123456', //aes 秘钥， 服务端和客户端必须保持一致
    'cipher' => 'AES-128-ECB', //aes 加密算法
    'app_sign_time' => 3000, //sign有效时间
    'app_sign_cache_time' => 60, //sign的缓存时间
    'app_types' => [
    	'ios',
    	'android',
    	'paid',
    ],
];
