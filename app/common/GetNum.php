<?php
namespace app\common;

use app\model\Order;
use iboxs\facade\Cookie;

class GetNum{
    /**
     * 生成订单号
     */
    public static function CreateOrderNum(){
        $a=date('ymd');
        $b=substr(str_replace('.','',microtime(true)),5,8);
        $c=rand(1000,9999);
        $num=$a.$b.$c;
        $count=Order::where('order_num',$num)->count();
        if($count>0){
            return self::CreateOrderNum();
        }
        return $num;
    }

    public static function GetOrderCookie(){
        $cookie=Cookie::get('ordercookie');
        if($cookie==null){
            $cookie=md5(microtime(true).rand(10000,99999));
            Cookie::forever('ordercookie',$cookie,7*3600*86400);
        }
        return $cookie;
    }
}