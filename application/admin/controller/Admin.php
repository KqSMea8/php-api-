<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use common\model\AdminUser;
use app\common\lib\IAuth;
use app\admin\controller\Base;

class Admin extends Base
{
    public function add()
    {
    	//判断是否是post提交
    	if (request()->isPost()) {
            $this->model = 'AdminUser';
    		$param = request()->param();
    		$validate = validate('AdminUser');
    		if (!$validate->check($param)) {
    			$this->error($validate->getError());
    		}

    		$param['password'] = IAuth::setPassword($param['password']);
    		$param['status'] = 1;
    		try {
    			$userId = model('AdminUser')->add($param);
    		} catch (\Exception $e) {
    			$this->error($e->getMessage());
    		}
    		if ($userId) {
    			$this->success('userId='.$userId.'用户新增成功');
    		} else {
    			$this->error('error');
    		}
    	} else {
        	return $this->fetch();
    	}
    }
}
