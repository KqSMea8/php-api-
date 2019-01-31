<?php

namespace app\common\model;

use think\Model;

class Base extends Model 
{
	protected $autoWriteTimestamp = true;

	/**
	 * 新增
	 * @param [type] $data [description]
	 */
	public function add($data) 
	{
		if (!is_array($data)) {
			exception('参数错误');
		}
		$this->allowField(true)->save($data);
		return $this->id;
	}
}