<?php
namespace iboxs\test;
require "../vendor/autoload.php";
use iboxs\payment\Client;
use iboxs\payment\Notify;

$alipayconfig=require("config/alipay.php");
$orderInfo=array(
    'order_name'=>"订单测试",
    'amount'=>1,
    'out_trade_no'=>"2021101247845"
);
$alipay=new Client($alipayconfig);
var_dump($alipay->AlipayWeb($orderInfo));   //发起网页支付（无返回值，系统会自动跳到支付宝网站支付，不用区分手机和电脑，系统自动区分）
// var_dump($alipay->AlipayCode($orderInfo));   //获取扫码支付的二维码

/**支付宝支付其他
 * $codeInfo=$alipay->AlipayCode($orderInfo);   //支付宝当面扫码支付（获得返回信息，自行提取二维码信息）
 * 
 * 支付宝其他操作（AlipayRefund 退款）
 * $result=$alipay->AlipayRefund($orderInfo);
 * 
 * 回调验证
 * $result=Notify::alipayNotify($alipayconfig);   //返回回调数组信息，若返回false的为验签失败
 */

?>