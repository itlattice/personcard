<?php
/**
 * 快速校验
 * @author  zqu
 */
namespace iboxs\payment;

use iboxs\payment\alipay\AlipayNotify;
use iboxs\payment\chpay\chEPay;
use iboxs\payment\wlpay\weiLanPay;
use iboxs\payment\wxpay\WxpayNotify;

/**
 * 回调验签
 */
class Notify
{
    /**
     * 支付宝验签（异步）
     * @param array $config 支付宝配置信息
     * @return bool|array 验签成功返回支付宝接收的回调信息，失败返回false
     */
    public static function alipayNotify($config)
    {
        $params=$_POST;
        $notify=new AlipayNotify($config);
        $result=$notify->rsaCheck($params);
        if($result===true){
            if($params['trade_status'] == 'TRADE_FINISHED' || $params['trade_status'] == 'TRADE_SUCCESS'){
                echo "success";
                return $params;
            }
        }
        return $result;
    }

    /**
     * 微信验签
     * @param array $config 微信配置信息
     * @return bool|array 验签成功返回回调信息，失败返回false
     */
    public static function WxPayNotify($config){
        header("Content-type: text/xml");
        $notify=new WxpayNotify($config['mchid'],$config['appid'],$config['apiKey']);
        $result=$notify->Check();
        $notifiedData = file_get_contents('php://input');
        //XML格式转换
        $xmlObj = simplexml_load_string($notifiedData, 'SimpleXMLElement', LIBXML_NOCDATA);
        $xmlObj = json_decode(json_encode($xmlObj), true);
            //支付成功
        if ($xmlObj['return_code'] == "SUCCESS" && $xmlObj['result_code'] == "SUCCESS") {
            if($result==true){
                echo sprintf("<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>");
                return $xmlObj;
            }
        }
        return false;
    }
    /**
     * QQ验签（与微信相同，直接使用微信的）
     * @param array $config QQ配置信息
     * @return bool|array 验签成功返回回调信息，失败返回false
     */
    public static function QqPayNotify($config){
        $notify=new WxpayNotify($config['mchid'],$config['appid'],$config['key']);
        $result=$notify->Check();
        return $result;
    }

    /**
     * 微蓝码支付回调验签
     */
    public static function wlPayNotify($config){
        $notify=new weiLanPay($config['key']);
        $result=$notify->Check();
        if($result==false){
            return false;
        }
        echo 'success';
        return $_GET;
    }

    /**
     * 彩虹易支付回调验签
     */
    public static function chPayNotify($config){
        $notify=new chEPay($config['key']);
        $result=$notify->Check($config);
        if($result==false){
            return false;
        }
        echo 'success';
        return $_GET;
    }
}