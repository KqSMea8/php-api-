<?php

namespace app\common\model;

use think\Model;
use app\common\model\Base;

class News extends Base 
{
    /**
     * 获取推荐的数据
     * @return [type] [description]
     */
    public function getPositionNormalNews($num = 20)
    {
        $where = [
            'status' => 1,
            'is_position' => 1,
        ];
        $order = ['id' => 'desc'];

        return $this->where($where)
            ->field($this->getListField()) //获取指定字段
            ->order($order)
            ->limit($num)
            ->select();
    }

    /**
     * 获取首页头图数据
     * @param  integer $num [description]
     * @return [type]       [description]
     */
    public function getIndexHeadNormalNews($num = 4)
    {
        $where = [
            'status' => 1,
            'is_head_figure' => 1
        ];
        $order = ['id' => 'desc'];
        return $this->where($where)
            ->field($this->getListField()) //获取指定字段
            ->order($order)
            ->limit($num)
            ->select();
    }

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
        if (!isset($condition['status'])) {
            $condition['status'] = ['neq', config('code.status_delete')];
        }

        $order = ['id' => 'desc'];

        // $start = ($param['page'] -1)*$param['size'];

        $result = $this->where($condition)
            ->field($this->getListField()) //获取指定字段
            ->limit($start, $size)
            ->order($order)
            ->select();
            // ->getLastSql();
            // var_dump($result);exit();
        // echo $this->getLastSql();

        return $result;
    }

    /**
     * 获取数据的总数
     * @param  [type] $param [description]
     */
    public function countNewsByCondition($condition = [])
    {
        if (!isset($condition['status'])) {
            $condition['status'] = ['neq', config('code.status_delete')];
        }

        $result = $this->where($condition)
            ->count();
        // echo $this->getLastSql();

        return $result;
    }

    /**
     * 获取排序榜数据
     * @param  array  $data [description]
     */
    public function getRankNormalNews($num = 5)
    {
        $data['status'] = ['eq', config('code.status_normal')];
        $order = ['read_count' => 'desc'];

        $result = $this->where($data)
            ->field($this->getListField())
            ->order($order)
            ->limit($num)
            ->select();
        // echo $this->getLastSql();
        return $result;
    }


    /**
     * 通用获取参数的数据字段
     * @return [type] [description]
     */
    private function getListField()
    {
        return  [
            'id',
            'catId',
            'image',
            'title',
            'read_count',
            'status',
            'is_position',
            'update_time',
            'create_time',
        ];
    }
}