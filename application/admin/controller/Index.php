<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\controller\base;
use think\Request;

class Index extends base
{
    public function index()
    {
    	// var_dump(session(config('admin.session_user'), '', config('admin.session_user_scope')));exit();
        return $this->fetch();
    }

    public function welcome()
    {
    	return 'welcome to home';
    }
}
