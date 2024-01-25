<?php
namespace iboxs\test;
require "../vendor/autoload.php";
use iboxs\payment\Client;

$wxpayconfig=require("config/wxpay.php");
$orderInfo=array(
    'order_name'=>"订单测试",
    'amount'=>1,
    'out_trade_no'=>"2021101247845"
);
$wxpay=new Client($wxpayconfig);
var_dump($wxpay->WxPayCode($orderInfo));

/**
 * 回调验证：
 * $result=Notify::WxPayNotify($wxpayconfig);   ////返回回调数组信息，若返回false的为验签失败
 */