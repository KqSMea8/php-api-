<?php

namespace app\common\lib;

use app\common\lib\Aes;
use app\common\lib\exception\ApiException;
use think\Cache;
use think\Log;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

 /**
  * 阿里云发送短信基础类库
  */
 class Aliyun 
 {
    /**
     * 静态变量保存全局的实例
     * @var null
     */
    private static $instance = null;

    /**
     * 私有的构造方法
     */
    private function __construct()
    {

    }

    /**
     * 静态方法 单例模式的统一入口
     * @return [type] [description]
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

         return self::$instance;
    }

    /**
     * 设置短信认证
     * @return [type] [description]
     */
    public function sendMsg($phone = 183573523672)
    {
        Log::info('sendMsgStart--------------');
        AlibabaCloud::accessKeyClient(config('aliyun.AccessKey'), config('aliyun.SecretKey'))
            ->regionId('cn-hangzhou') // replace regionId as you need
            ->asGlobalClient();
        $code = rand(10000, 99999);

        try {
            $result = AlibabaCloud::rpcRequest()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options([
                            'query' => [
                                'PhoneNumbers' => $phone,
                                'SignName' => config('aliyun.SignName'),
                                'TemplateCode' => config('aliyun.TemplateCode'),
                                'TemplateParam' => json_encode(array(  // 短信模板中字段的值
                                    "bname"=> $code,
                                    "rname"=>'测试石盾rname', //
                                    "uri"=>'uri'
                                    ), JSON_UNESCAPED_UNICODE
                                ),
                            ],
                        ])
                ->request();
            if ($result->Code == "OK") {
                //设置验证码失效时间
                Cache::set($phone, $code, config('aliyun.identify_time'));
                return true;
            }
            Log::info('sendMsgError---------'.json_encode($result->toArray()));
            return false;
        } catch (ClientException $e) {
            Log::info('sendMsgError---------'.$e->getMessage());
            return false;
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            return false;
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }

    /**
     * 根据手机号查询验证码是否正确
     * @param  integer $phone [description]
     * @return [type]         [description]
     */
    public function checkSmsIdentify($phone = 18357352367)
    {
        return Cache::get($phone);
    }

 }