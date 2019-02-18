<?php
namespace app\api\controller;

use think\Controller;
use think\Request;
use app\common\lib\exception\ApiException;
use app\api\controller\Common;
/**
 * 
 */
class Test extends Common
{
    public function index()
    {
        return '222';
    }

    public function update(Request $request, $id = 0)
    {
        $data = $request->param();
        return json_encode($data);
    }

    public function delete(Request $request, $id = 0)
    {
        $data = $request->param();
        return json_encode($data);
    }

    /**
     * post 新增
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function save(Request $request)
    {
        //api接口处理异常的一共方法
        // try {
        //     model('sadasd');
        // } catch (\Exception $e) {
        //     return show(0, $e->getMessage(), 400);
        // }
        // $data['saa'];
        // model('sadasd');

        //获取数据插入库中
        //给客户端app => 接口数据
        $data = $request->param();
        // if ($data['mt'] != 1) {
        //     throw new ApiException('传入的参数不合法', 400);
        // }
        // var_dump(config('default_return_type'));exit();
        // var_dump($data);exit();
        // $return = [
        //     'status' => 1,
        //     'message' => 'ok',
        //     'data' => $data,
        // ];
        // return json($return, 202);\
        return show(1, 'ok', $data);
    }
}
