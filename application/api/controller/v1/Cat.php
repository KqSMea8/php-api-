<?php
namespace app\api\controller\v1;

use think\Controller;
use think\Request;
use app\common\lib\exception\ApiException;
use app\api\controller\Common;
/**
 * 
 */
class Cat extends Common
{
    /**
     * 栏目接口
     * @return [type] [description]
     */
    public function read()
    {
        $cats = config('cat.lists');
        // halt($cats);
        $result[] = [
            'catId' => 0,
            'catName' => '首页',
        ];

        // //不用版本要求不一样不能这样写
        // if ($this->headers['version'] == 1) {
        //  halt(213213);
        // }

        foreach ($cats as $key => $value) {
            $result[] = [
                'catId' => $key,
                'catName' => $value,
            ];
        }
        return show(config('code.success'), 'ok', $result, 200);
    }
}
