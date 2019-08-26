<?php


namespace App\Utils;
use App\Utils\CurlPesponse;

class HttpUtils
{
    /**
     * 发送post请求
     * @param string $url 请求地址
     * @param array $post_heads 请求头信息
     * @param string $post_data post xml格式数据
     * @return string
     */
    public function send_post($url, $post_heads, $post_data, $keepalive) {
        $ch = curl_init(); //初始化CURL句柄

        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
        curl_setopt($ch, CURLOPT_HEADER,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $post_heads);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST"); //设置请求方式
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,true);
        curl_setopt($ch, CURLOPT_TIMEOUT ,100000);
        if ($post_data != null)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);//设置提交的字符串
        $response = curl_exec($ch);

        if (!$keepalive) {
            curl_close($ch);
        }

        return new CurlPesponse($response);
    }

    /**
     * 获取相应头中的 cookie
     */
    public function getResponseCooki($header) {
        preg_match_all('/Set-Cookie:(.*);/iU',$header,$str);
        return $str;
    }

    /**
     * 获取相应体中的 sessionid
     */
    public function getSessionId($content) {
        $res = preg_match('/<sessionId>(.*)<\/sessionId>/iU',$content,$str);
        if($res == 1){
            return $str[1];
        }else{
            return null;
        }
    }

    public function getResponseJson($content) {
        $json = json_decode($content,true);
        return $json;
    }

    /**
     */
    function str_replace_limit($search, $replace, $subject, $limit=-1) {

        if (is_array($search)) {
            foreach ($search as $k=>$v) {
                $search[$k] = '`' . preg_quote($search[$k],'`') . '`';
            }
        }
        else {
            $search = '`' . preg_quote($search,'`') . '`';
        }

        return preg_replace($search, $replace, $subject, $limit);
    }
}
