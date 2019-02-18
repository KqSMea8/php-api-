<?php

use think\Route;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],

// ];

//get 
Route::get('test', 'api/test/index');
Route::put('test/:id', 'api/test/update'); //修改
Route::delete('test/:id', 'api/test/delete'); //删除

Route::resource('test', 'api/test');//post => api/test save();

// Route::resource('cat', 'api/cat');//post => api/test save();
Route::get('api/:ver/cat', 'api/:ver.cat/read');