<?php
namespace app\api\controller\v1;

use think\Controller;
use think\Request;
use app\common\lib\exception\ApiException;
use app\api\controller\Common;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use app\common\lib\Aliyun;

/**
 * 
 */
class Index extends Common
{
    /**
     * 获取首页接口
     * 1 头图
     * 2 推荐位列表 默认40条
     * @return [type] [description]
     */
    public function index()
    {
        $heads = model('News')->getIndexHeadNormalNews();
        $heads = $this->getDealNews($heads);

        $positions = model('News')->getPositionNormalNews();
        $positions = $this->getDealNews($positions);
        
        $return['heads'] = $heads;
        $return['positions'] = $positions;
        return show(config('code.success'), 'ok', $return, 200);
    }

    /**
     * 客户端初始化接口
     * 1、监测app是否需要升级
     * @return [type] [description]
     */
    public function init()
    {
        //app_type 去ent_version 查询是否需要升级
        $version = model('Version')->getLastVersionByAppType($this->headers['app-type']);
        if (empty($version)) {
            return new ApiException('error', 400);
        }

        if ($version->version > $this->headers['version']) {
            $version->is_update = $version->is_force ==1 ? 2 : 1;
        } else {
            $version->is_update = 0; //0不需要更新 1要更新 2强制更新
        }

        //记录用户的基本信息
        $actives = [
            'version' => $this->headers['version'],
            'app_type' => $this->headers['app-type'],
            'model' => $this->headers['model'],
            'did' => $this->headers['did'],
        ];
        try {
            model('AppActive')->save($actives);
        } catch (\Exception $e) {
            var_dump($e->getMessage());exit();
        }

        return show(config('code.success'), 'ok', $version, 200);
    }

    public function sendMsg()
    {
        $aliyun = Aliyun::getInstance();
        $aa = $aliyun->sendMsg();
        var_dump($aa);exit();
    }
}
