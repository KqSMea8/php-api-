<?php

namespace app\common\validate;

use think\Validate;

class AdminUser extends Validate 
{
	protected $rule = [
		'username' => 'require|max:20',
		'password' => 'require|max:20',
	];

    protected $message = [
        'username.require' => '赛用户名不能为空',
        'password.require' => '密码不能为空',
        'username.max' => '用户名长度不能超过20',
        'password.max' => '密码长度不能超过20',
    ];

    protected $scene = [
        // 'add' => ['username', 'password'],
        // 'edit' => ['username', 'password'],
    ];
}