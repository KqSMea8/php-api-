<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function pagination($obj)
{
	if (!$obj) {
		return '';
	}
	$params = request()->param();
	return '<div class="imooc-app">'.$obj->appends($params)->render().'</div>';
}

/**
 * 获取栏目名称
 * @param  [type] $catId [description]
 */
function getCatName($catId)
{
	if (empty($catId)) {
		return '';
	}
	$cats = config('cat.lists');
	return empty($cats[$catId]) ? '' : $cats[$catId];
}
/**
 * 是否推荐
 * @param  [type] $is_position [description]
 */
function isYesNo($is_position)
{
	return empty($is_position) ? '<span> 否 </span>' : '<span style="color: red"> 是 </span>';
}

/**
 * 状态
 * @param  [type] $id     [description]
 * @param  [type] $status [description]
 * @return [type]         [description]
 */
function status($id, $status)
{
    $controller = request()->controller();

    $sta = $status == 1? 0 :1;
    $url = url($controller.'/status', ['id' => $id, 'status' => $sta]);

    if ($status == 1) {
    	$str = "<a href='javascript:;' title='修改状态' status_url='". $url ."' onclick='app_status(this)'><span class='label label-success radius'>正常</span></a>";
    } elseif ($status == 0) {
    	$str = "<a href='javascript:;' title='修改状态' status_url='". $url ."' onclick='app_status(this)'><span class='label label-danger radius'>待审核</span></a>";
    }
    return $str;
}

/**
 * 通用化API接口数据输出
 * @param  [type] $status   业务状态码
 * @param  [type] $message  信息提示
 * @param  [type] $data     数据
 * @param  [type] $httpCode http状态码
 * @return array       
 */
function show($status, $message, $data = [], $httpCode = 200)
{
    $return = [
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ];
	return json($return, $httpCode);
}