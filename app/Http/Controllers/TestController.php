<?php


namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class TestController extends BaseController
{
    /**
     * 测试方法1
     */
    public function test1(Request $request)
    {
        $str = $request->fullUrl();
        return "测试方法1调用成功！ 请求地址=$str";
    }

    /**
     * 测试方法2
     */
    public function test2(Request $request)
    {

//        $heads = [
//            'Content-Type:application/json; charset=UTF-8',
//            'Accept-Encoding:gzip',
//            'User-Agent:Jetty/9.2.24.v20180105',
//            'Authorization:'
//        ];
//
//        array_push($heads, count($heads), '新元素');
//        return $heads;

        $str='-------</div>*******</div>++++++++</div>';
        $indes = preg_match('</div>',$str, $match);
//        return $indes;
        return var_dump($match[0]);
    }
}
