<?php


namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Utils\HttpUtils;
use App\Utils\StreamingUtils;

class SoapCallOutController extends BaseController
{

    /**
     * Use the SoapWrapper
     */
    public function loginSF()
    {

        $id = 1;

        //定义cooiks数组
        $cookis_array=array();

        //创建工具类实例
        $httpUtils = new HttpUtils();
        $streamingUtils = new StreamingUtils();

        //调用Salerforce login 认证接口
        $result = $streamingUtils->loginSF();
        //获取response携带的cookie信息（数组）
        $cookis = $httpUtils->getResponseCooki($result->getHeader());
        //将cookie信息保存至$cookis_array
        $cookis_array = $this->storeCookie($cookis_array, $cookis);
        //获取sessionid
        $sessionid = $httpUtils->getSessionId($result->getContent());


        //调用Streaming API 握手请求
        $result = $streamingUtils->doHandshake($sessionid, $cookis_array, $id);
        //获取response携带的cookie信息（数组）
        $cookis = $httpUtils->getResponseCooki($result->getHeader());
        //将cookie信息保存至$cookis_array
        $cookis_array = $this->storeCookie($cookis_array, $cookis);
        //获取相应体Json对象
        $responseJson = $httpUtils->getResponseJson($result->getContent());
        $id++;

        //获取clientId
        $clientId = $responseJson[0]["clientId"];

        //调用Streaming API 连接请求
        $result = $streamingUtils->doConnect($sessionid, $clientId, $cookis_array, $id);
        $id++;
//        //获取response携带的cookie信息（数组）
//        $cookis = $httpUtils->getResponseCooki($result->getHeader());
//        //将cookie信息保存至$cookis_array
//        $cookis_array = $this->storeCookie($cookis_array, $cookis);
//        $id++;
//
        //再次调用Streaming API 连接请求
        $result = $streamingUtils->doConnect($sessionid, $clientId, $cookis_array, $id);
        $id++;

        //再次调用Streaming API 连接请求
        $result = $streamingUtils->doConnect($sessionid, $clientId, $cookis_array, $id);
        $id++;

        //再次调用Streaming API 连接请求
        $result = $streamingUtils->doConnect($sessionid, $clientId, $cookis_array, $id);
        $id++;

//        return $cookis_array;
//        return $result->getHeader();
        return $result->getContent();
//        return $result;

//        $cooki = $httpUtils->getResponseCooki($result);
//        return $cooki;

//        return "Soap测试方法调用成功！ 返回=$result";
    }

    /**
     * 握手请求范例（Success）
     */
    public function testHandshake()
    {
        //使用方法
        $sessionid = '';

        $url = 'https://lix-dev-ed.my.salesforce.com/cometd/43.0/handshake';
        $heads = [
            'Content-Type:application/json; charset=UTF-8',
            'Accept-Encoding:gzip',
            'User-Agent:Jetty/9.2.24.v20180105',
            'Authorization:00D7F000007DO16!ARMAQEKVUcOOAHuq9d3oRRmbfb_pYXolBzfgXhlmwx7wntHfmbeo8g8RfhV8J24tDTwsJ5o4jxmFcIBvW7j_rOHFIc22CjI2'
        ];
        $data = '{
                    "channel": "/meta/handshake",
                    "version": "1.0",
                    "supportedConnectionTypes": ["long-polling", "callback-polling", "iframe"]
                }';

        $httpUtils = new HttpUtils();
        $result = $httpUtils->send_post($url, $heads, $data);

//        $cooki = $httpUtils->getResponseCooki($result);
//        return $cooki;

        $fan_jn = json_decode($result,true);
        $clientid = $fan_jn[0]["clientId"];

        return $result;
//        return $this->testConnect($clientid);
    }

    /**
     * 链接请求范例（testing）
     */
    public function testConnect($clientid)
    {
        //使用方法
        $sessionid = '';

        $url = 'https://lix-dev-ed.my.salesforce.com/cometd/43.0/connect';
        $heads = [
            'Content-Type:application/json; charset=UTF-8',
            'Accept-Encoding:gzip',
            'User-Agent:Jetty/9.2.24.v20180105',
            'Authorization:00D7F000007DO16!ARMAQEKVUcOOAHuq9d3oRRmbfb_pYXolBzfgXhlmwx7wntHfmbeo8g8RfhV8J24tDTwsJ5o4jxmFcIBvW7j_rOHFIc22CjI2'
        ];
        $data = '{
                    "channel": "/meta/connect",
                    "clientId": "' . $clientid . '",
                    "connectionType": "long-polling"
                }';

        $httpUtils = new HttpUtils();
        $result = $httpUtils->send_post($url, $heads, $data);

        return "Soap测试方法调用成功！ 返回=$result";
    }

//    /**
//     * 发送post请求
//     * @param string $url 请求地址
//     * @param array $post_heads 请求头信息
//     * @param string $post_data post xml格式数据
//     * @return string
//     */
//    public function send_post($url, $post_heads, $post_data) {
//        $ch = curl_init(); //初始化CURL句柄
//        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
//        curl_setopt ($ch, CURLOPT_HTTPHEADER, $post_heads);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST"); //设置请求方式
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);//设置提交的字符串
//        $output = curl_exec($ch);
//        curl_close($ch);
//        return $output;
//    }

    public function storeCookie($arr, $cookis){
        for($i=0; $i<count($cookis[0]); $i++){
            array_push($arr, $cookis[0][$i]);
        }
        return $arr;
    }
}
