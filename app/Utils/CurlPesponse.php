<?php


namespace App\Utils;


class CurlPesponse
{
    private $header;
    private $content;

    //定义一个构造方法初始化赋值
    public function __construct($response) {
        // 解析HTTP数据流
        list($header, $content) = explode("\r\n\r\n", $response);
        $this->header=$header;
        $this->content=$content;
    }

    public function getHeader(){
        return $this->header;
    }

    public function getContent(){
        return $this->content;
    }
}
