<?php

namespace app\common\model;

use think\Model;
use app\common\model\Base;

class Version extends Base 
{
	/**
	 * 通过appTpe获取最新一条数据
	 * @param  string $appType [description]
	 * @return [type]          [description]
	 */
	public function getLastVersionByAppType($appType = '')
	{
        $where = [
            'status' => 1,
            'app_type' => $appType,
        ];
        $order = ['id' => 'desc'];

        return $this->where($where)
            ->order($order)
            ->limit(1)
            ->find(); //取一条用find  多条 select
	}
}