<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\controller\base;
use think\Request;
use app\common\lib\upload;

/**
 * 图片上传
 */
class Image extends base
{
	/*
	七牛云上传图片
	 */
	public function upload(Request $request)
	{
		$file = $request->file('file');
		try {
			$image = upload::images($file);
		} catch (\Exception $e) {
			return json_encode(['status' => 0, 'message' => $e->getMessage]);
		}

		if ($image) {
	    	$data = [
	    		'status' => 1,
	    		'message' => '成功',
	    		'data' => config('qiniu.image_url').'/'.$image,
	    	];
	    	return json_encode($data);
		} else {
			return json_encode(['status' => 0, 'message' => '上传失败']);
		}
	}

	/**
	 * 本地图片上传
	 * @return [type] [description]
	 */
    public function localupload(Request $request)
    {
    	$file = $request->file('file');//对应上传组件参数fileObjName
    	//把图片上传指定文件夹
    	$info = $file->move('upload');
    	if ($info && $info->getPathName()) {
	    	$data = [
	    		'status' => 1,
	    		'message' => '成功',
	    		'data' => '/'.$info->getPathName(),
	    	];
	    	return json_encode($data);
    	}
    	$data = [
    		'status' => 0,
    		'message' => '失败',
    		'data' => '',
    	];
    	return json_encode($data);
    	// var_dump($info);exit();
    	// $data = [
    	// 	'status' => 1,
    	// 	'data' => 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1549420218&di=119ffc5a1adaa0f3201ab09ffbbe032c&imgtype=jpg&er=1&src=http%3A%2F%2Fwww.diannaodian.com%2Fuploads%2Fallimg%2F120529%2F0S5243N5-1.jpg',
    	// ];
     //    echo json_encode($data);
    }
}
