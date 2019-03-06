<?php
namespace app\api\controller\v1;

use think\Controller;
use think\Request;
use app\common\lib\exception\ApiException;
use app\api\controller\Common;
/**
 * 
 */
class Rank extends Common
{
    /**
     * 获取排行榜数据列表
     * 1 获取数据库按read_count排序
     * 2 优化 redis 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        try {
            $ranks = model('News')->getRankNormalNews();
            $ranks = $this->getDealNews($ranks);
        } catch(\Exception $e) {
            throw new ApiException('数据库操作失败', 400);
        }
        return show(config('code.success'), 'ok', $ranks, 200);
    }
}
