<?php

namespace app\common\lib;
 /**
  * 
  */
 //引入鉴权类
use \Qiniu\Auth;
use Qiniu\Storage\UploadManager;

/**
 * 七牛云上传图片基础类库
 */
 class upload 
 {
 	/*
 	图片上传
 	 */
 	public static function images($file)
 	{
 		$info = $file->getInfo();
 		if (empty($info)) {
 			exception('提交图片不存在', 404);
 		}
 		//要上传的临时文件
 		$tmp_name = $info['tmp_name'];
 		$ext = explode('.', $info['name']);
 		$ext = $ext[1];

 		//构建一个鉴权
	    $auth = new Auth(config('qiniu.accessKey'), config('qiniu.secretKey'));
	    $token = $auth->uploadToken(config('qiniu.bucketName'));
	    //上传到七牛后保存的文件名
	    $key = date('Y/m/d').substr(md5($tmp_name), 0, 12).rand(0,99999).'.'.$ext;

	    //初始化 UploadManager
	    $upManager = new UploadManager();
	    list($ret, $error) = $upManager->putFile($token, $key, $tmp_name);

	    if ($error !== null) {
	    	return null;
	    } else {
	    	return $key;
	    }
 	}
 }