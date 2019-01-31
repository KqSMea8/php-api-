<?php

namespace app\common\model;

use think\Model;
use app\common\model\Base;

class News extends Base 
{
    /**
     * 后台自动化分页
     * @param  array  $data [description]
     */
    public function getNews($data = [])
    {
        $data['status'] = ['neq', config('code.status_delete')];
        $order = ['id' => 'desc'];
        $result = $this->where($data)
            ->order($order)
            ->paginate();
        // echo $this->getLastSql();
        return $result;
    }

    /**
     * 根据条件来获取列表的数据
     * @param  [type] $param [description]
     */
    public function getNewsByCondition($condition = [], $start = 0, $size = 10)
    {
        $condition['status'] = ['neq', config('code.status_delete')];
        $order = ['id' => 'desc'];

        // $start = ($param['page'] -1)*$param['size'];

        $result = $this->where($condition)
            ->limit($start, $size)
            ->order($order)
            ->select();
        // echo $this->getLastSql();
        
        return $result;
    }

    /**
     * 获取数据的总数
     * @param  [type] $param [description]
     */
    public function countNewsByCondition($condition = [])
    {
        $condition['status'] = ['neq', config('code.status_delete')];

        $result = $this->where($condition)
            ->count();
        // echo $this->getLastSql();

        return $result;
    }
}