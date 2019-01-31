<?php

namespace app\common\lib;
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
 }