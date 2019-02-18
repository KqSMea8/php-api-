<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

/**
 * 后台基础类库
 */
class Base extends Controller
{
    public $page = '';//当前页
    public $size = '';//每页数量
    public $start = 0;//起始值
    public $model = '';//定义model
	/**
	 * 初始化方法
	 * @return [type]
	 */
    protected function _initialize()
    {
    	//判断用户是否登录
    	$isLogin = $this->isLogin();
    	if (!$isLogin) {
    		return $this->redirect('login/index');
    	}
    }

    /**
     * 判断是否登录
     * @return boolean
     */
    public function isLogin()
    {
    	//获取session
    	$user = session(config('admin.session_user'), '', config('admin.session_user_scope'));
    	if ($user && $user->id) {
    		return true;
    	} 
    	return false;
    }

    /**
     * 获取分页的page size
     * @return [type] [description]
     */
    public function getPageSize($data)
    {
        $this->page = empty($data['page']) ? 1 : $data['page'];
        $this->size = empty($data['size']) ? config('paginate.list_rows') : $data['size'];
        $this->start = ($this->page -1) * $this->size;
    }

    /**
     * 删除逻辑
     * @param  Request $reuest [description]
     * @return [type]          [description]
     */
    public function delete(Request $reuest, $id = 0)
    {
        if (!intval($id)) {
            return $this->result('', 0, 'Id不合法');
        }
        //如果表和控制器文件名一样 news news
        //但是控制器名和表面不一样
        $model = $this->model ? $this->model : request()->controller();
        //如果php php7  $model = $this->model ?? request()->controller(); 功能和上面一致

        try {
            $res = model($model)->save(['status' => config('code.status_delete')], ['id' => $id]);
        } catch(\Exception $e) {
            return $this->result('', 0, $e->getMessage());
        }

        if ($res) {
            return $this->result(['jump_url' => $_SERVER['HTTP_REFERER']], 1, 'ok');
        }
        return $this->result('', 0, '删除失败');
    }

    /**
     * 通用化修改状态
     * @param  Request $reuest [description]
     * @param  integer $id     [description]
     * @return [type]          [description]
     */
    public function status(Request $request)
    {
        $data = $request->param();
        //tp5 validate机制
        
        //通过id 去库中查询记录是否存在
        
        //model('News')->get($data['id']);
        //
        $model = $this->model ? $this->model : request()->controller();

        try {
            $res = model($model)->save(['status' => $data['status']], ['id' => $data['id']]);
        } catch(\Exception $e) {
            return $this->result('', 0, $e->getMessage());
        }

        if ($res) {
            return $this->result(['jump_url' => $_SERVER['HTTP_REFERER']], 1, 'ok');
        }
        return $this->result('', 0, '修改失败');
    }
}
