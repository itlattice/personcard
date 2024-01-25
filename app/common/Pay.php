<?php
namespace app\common;

use app\common\utils\pay\{Alipay,CodePay,Epay,Wechat,AlipayCode};

class Pay{
    /**
     * 启动支付
     */
    public static function startPay($order,$account){
        $key=$account['key'];
        $class=[];
        switch($key){
            case 'alipay': $class=new Alipay($order,$account);break;//支付宝官方支付
            case 'alipaycode': $class=new AlipayCode($order,$account);break;//支付宝当面付
            case 'wechat': $class=new Wechat($order,$account);break; //微信官方支付
            case 'epay': $class=new Epay($order,$account);break; //易支付
            case 'codepay': $class=new CodePay($order,$account);break; //码支付
        }
        return $class->Main();
    }
}