<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use common\model\AdminUser;
use app\common\lib\IAuth;
use think\Session;
use app\admin\controller\base;

class Login extends base
{
    //覆盖base的init
    protected function _initialize()
    {
    }

    public function index()
    {
        //如果后台用户已经登录 跳转到后台首页
        $isLogin = $this->isLogin();
        if ($isLogin) {
            return $this->redirect('index/index');
        }
        return $this->fetch();
    }

    public function check(Request $request)
    {
        if ($request->isPost()) {
            $data = request()->param();
            if (!captcha_check($data['code'])) {
                $this->error('验证码不正确');
            }
            //验证参数
            // validate('AdminUser')->check($data);
            try {
                $user = model('AdminUser')->get(['username' => $data['username']]);
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }

            if (empty($user) || $user->status != config('code.status_normal')) {
                return $this->error('该用户不存在');
            }

            //对密码进行校验
            if ($user->password != IAuth::setPassword($data['password'])) {
                return $this->error('密码不正确');
            }
            $udata = array(
                'last_login_time' => time(),
                'last_login_ip' => request()->ip(),
            );

            try {
                $update = model('AdminUser')->save($udata, ['id' => $user->id]);
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            session(config('admin.session_user'), $user, config('admin.session_user_scope'));
            $this->success('登录成功', 'index/index');
        } else {
            return $this->error('请求不合法');
        }
    }

    //退出
    //1清楚session 2跳转到登录页
    public function logout()
    {
        session(null, config('admin.session_user_scope'));
        $this->redirect('login/index');
    }
}
