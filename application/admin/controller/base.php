<?php
namespace app\admin\controller;

use think\Controller;

/**
 * 后台基础类库
 */
class base extends Controller
{
    public $page = '';//当前页
    public $size = '';//每页数量
    public $start = 0;//起始值
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
}
