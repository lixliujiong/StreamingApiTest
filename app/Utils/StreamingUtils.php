<?php


namespace App\Utils;
use App\Utils\HttpUtils;

class StreamingUtils
{



    /**
     * 调用salesforce login认证接口（SOAP），获取其返回值中的sessionid
     */
    public function loginSF()
    {
        //使用方法
        $username = 'lix.liu@celent.com.cn';
        $password = 'lix1046375641';

        $url = 'https://login.salesforce.com/services/Soap/u/46.0';
        $heads = [
            'Content-Type:text/xml; charset=UTF-8',
            'SOAPAction:login'
        ];
        $data = '<?xml version="1.0" encoding="utf-8" ?>
                    <env:Envelope xmlns:xsd="http://www.w3.org/2001/XMLSchema"
                        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                        xmlns:env="http://schemas.xmlsoap.org/soap/envelope/">
                      <env:Body>
                        <n1:login xmlns:n1="urn:partner.soap.sforce.com">
                          <n1:username>' . $username . '</n1:username>
                          <n1:password>' . $password . '</n1:password>
                        </n1:login>
                      </env:Body>
                    </env:Envelope>';

        $httpUtils = new HttpUtils();
        return $httpUtils->send_post($url, $heads, $data, false);
    }

    /**
     * 调用 Salesforce Streaming API 握手请求
     */
    public function doHandshake($sessionid, $cookis, $id)
    {
        $url = 'https://lix-dev-ed.my.salesforce.com/cometd/43.0/handshake';
        $heads = [
            'Content-Type:application/json; charset=UTF-8',
            'Accept-Encoding:gzip',
            'User-Agent:Jetty/9.2.24.v20180105',
            'Authorization:' . $sessionid
        ];
        $heads = $this->addCookies($heads, $cookis);
        $data = '{
                    "ext":{"replay":true},
                    "channel": "/meta/handshake",
                    "version": "1.0",
                    "supportedConnectionTypes": ["long-polling"],
                    "id":' . $id . '
                }';

        $httpUtils = new HttpUtils();
        return $httpUtils->send_post($url, $heads, $data, true);
    }

    /**
     * 链接请求范例（testing）
     */
    public function doConnect($sessionid, $clientid, $cookis, $id)
    {
        $url = 'https://lix-dev-ed.my.salesforce.com/cometd/43.0/connect';
        $heads = [
            'Content-Type:application/json; charset=UTF-8',
            'Accept-Encoding:gzip',
            'User-Agent:Jetty/9.2.24.v20180105',
            'Authorization:' . $sessionid
        ];
        $heads = $this->addCookies($heads, $cookis);
        $data = '{
                    "channel": "/meta/connect",
                    "clientId": "' . $clientid . '",
                    "connectionType": "long-polling",
                    "advice":{"timeout":0},
                    "id":' . $id . '
                }';

//        return $heads;
        $httpUtils = new HttpUtils();
        return $httpUtils->send_post($url, $heads, $data, true);
    }

    public function addCookies($heads, $cookies){
        for($i=0; $i<count($cookies); $i++){
            array_push($heads, str_replace("Set-Cookie:",'Cookie:', substr($cookies[$i],0,strlen($cookies[$i])-1)));
        }
        return $heads;
    }
}
