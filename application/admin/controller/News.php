<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\controller\base;
use think\Request;

class News extends base
{
    public function index(Request $reuest)
    {
        $where = [];
        $data = $reuest->param();
        $query = http_build_query($data);

        if (!empty($data['start_time']) && !empty($data['end_time']) && $data['end_time'] >= $data['start_time']) {
            $where['create_time'] = [
                ['gt', strtotime($data['start_time'])],
                ['lt', strtotime($data['end_time'])],
            ];
        }
        if (!empty($data['catId'])) {
            $where['catId'] = intval($data['catId']);
        }
        if (!empty($data['title'])) {
            $where['title'] = ['like', '%'.$data['title'].'%'];
        }
        //获取数据，填充到模板 分页模式1
        // $news = model('News')->getNews();
        
        //分页模式2 
        // page size start 

        $this->getPageSize($data);
        // $where['page'] = $this->page;
        // $where['size'] = $this->size;

        //获取数据
        $news = model('News')->getNewsByCondition($where, $this->start, $this->size);
        //获取满足条件数据的总数
        $total = model('News')->countNewsByCondition($where);
        //结合总数+size 算出页数
        $pageTotal = ceil($total/$this->size);

        return $this->fetch('', [
            'cats' => config('cat.lists'),
            'news' => $news,
            'pageTotal' => $pageTotal,
            'curr' => $this->page,
            'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
            'catId' => empty($data['catId']) ? '' : $data['catId'],
            'title' => empty($data['title']) ? '' : $data['title'],
            'query' => $query,
        ]);
    }

    public function add(Request $reuest)
    {
        if ($reuest->isPost()) {
            $data = $reuest->post();
            //数据需要做校验 validate自行处理
            //入库操作
            try {
                $id = model('News')->add($data);
            } catch (\Exception $e) {
                return $this->result('', 0, '新增失败');
            }

            if ($id) {
                return $this->result(['jump_url' => url('news/index')], 1, 'ok');
            } else {
                return $this->result('', 0, '新增失败');
            }
        }
        return $this->fetch('', [
            'cats' => config('cat.lists'),
        ]);
    }
}
