<?php
namespace app\api\controller;

use think\Controller;
use think\Request;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use app\common\lib\IAuth;
use app\common\lib\Time;
use think\Cache;

/**
 * Api模块公共的控制器
 */
class Common extends Controller
{
    public $page = 1;//当前页
    public $size = 10;//每页数量
    public $start = 0;//起始值

    /**
     * header头
     * @var string
     */
    public $headers = '';
    /**
     * 初始化方法
     * @return [type]
     */
    public function _initialize()
    {
        // $data = [
        //     'aaa' => '123213',
        //     'name' => 'shidun',
        //     'version' => '2',
        //     'time' => Time::get13TimeStamp(),
        // ];
        // $sign = IAuth::setSign($data);
        // var_dump($data);
        // var_dump($sign);
        // exit();
        //924D8164A70AD9A75A76D4E6803F6E6438339D79443F76E0A537DB31899ABF84C8CDEDC6750CE09E20E738B900CCD09AA628B939C352B305147AA00B491111F1

        // $tiem = Time::get13TimeStamp();

        //判断api是否能权限使用
        if (config('app_debug') == false) {
            $this->checkRequestAuth();
        }
        $headers = request()->header();
        $this->headers = $headers;
        // $this->testAes();
    }

    /**
     * 检查每次app请求的数据是否合法
     * @return [type] [description]
     */
    public function checkRequestAuth()
    {

        // 924D8164A70AD9A75A76D4E6803F6E6438339D79443F76E0A537DB31899ABF84275DE8B431CBC02BDE1244CF379BDF8BB2F3D8314B101EF47D026F03CA072DDA
        //首先需要获取headers的数据
        $headers = request()->header();

        //sign 加密需要 客户端工程师， 解密:服务端工程师
        //
        //基础参数校验
        if (empty($headers['sign'])) {
            throw new ApiException('sign不存在', 400);
        }
        if (!in_array($headers['app-type'], config('app.app_types'))) {
            throw new ApiException('app-type不合法', 400);
        }

        //校验sign合法性
        if (!IAuth::checkSignPass($headers)) {
            throw new ApiException('授权码验证失败', 401);
        }
        //文件缓存到runtime中
        Cache::set($headers['sign'], 1, config('app_sign_cache_time'));
        // 1. 文件缓存 2.mysql 3.redis
    }

    public function testAes()
    {
        // $data = [
        //     'aaa' => '123213',
        //     'name' => 'shidun',
        //     'version' => '2',
        //     'time' => time(),
        // ];
        // $sign = IAuth::setSign($data);
        // var_dump($sign);exit();

        $str = '924D8164A70AD9A75A76D4E6803F6E6438339D79443F76E0A537DB31899ABF84869C85E9D033A176FEEA29C1DA7D57B2B2F3D8314B101EF47D026F03CA072DDA';
        echo (new Aes())->decrypt($str);exit();
        // $str = 'id=1&ms=shidun&page=1';
        // $aes = new Aes();
        // $aes_str = '6477f3ec9bf056752f50e77812d8c4846e3c44d2f16efa75cbd9f027e6f80321';
        // //加密
        // // echo $aes->encrypt($str); exit();
        // // 
        // // 解密
        // echo $aes->decrypt($aes_str); exit();
    }

    /**
     * 获取处理的新闻的内容数据
     * @param  array  $news [description]
     * @return [type]       [description]
     */
    protected function getDealNews($news = [])
    {
        if (empty($news)) {
            return [];
        }
        $cats = config('cat.lists');

        foreach ($news as $key => $new) {
            $news[$key]['catName'] = isset($cats[$new['catId']]) ? $cats[$new['catId']] : '--';
        }
        return $news;
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
