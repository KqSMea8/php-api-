<?php
namespace app\api\controller\v1;

use think\Controller;
use think\Request;
use app\common\lib\exception\ApiException;
use app\api\controller\Common;
/**
 * 
 */
class News extends Common
{
    public function index(Request $request)
    {
        // 校验
        $data = input('get.');
        $this->getPageSize($data);
        $condition['status'] = config('code.status_normal');
        if (!empty($data['catId'])) {
            $condition['catId'] = input('catId');
        }

        if (!empty($data['title'])) {
            $condition['title'] = ['like', '%'.$data['title'].'%'];
        }

        $count = model('News')->countNewsByCondition($condition);
        $news = model('News')->getNewsByCondition($condition, $this->start, $this->size);


        $return = [
            'total' => $count,
            'page_num' => ceil($count / $this->size),
            'list' => $this->getDealNews($news),
        ];

        return show(config('code.success'), 'ok', $return, 200);
    }

    /**
     * 获取详情页数据
     * @return [type] [description]
     */
    public function read(Request $request)
    {
        // 详情页面 app  1 xxx.com/3.html  2 接口 
        $id = input('id', 0, 'intval');
        if (empty($id)) {
            throw new ApiException('参数为空', 400);
        }
        //通过id获取数据
        $news = model('News')->get($id);
        if (empty($news) || $news->status != config('code.status_normal')) {
            throw new ApiException('不存在该新闻', 404);
        }
        try {
            model('News')->where('id', $id)->setInc('read_count');
        } catch (\Exception $e) {
            throw new ApiException('更新失败', 400);
        }
        $cats = config('cat.lists');
        $news->catName = $cats[$news->catId];
        return show(config('code.success'), 'ok', $news, 200);
    }
}
