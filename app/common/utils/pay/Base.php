<?php
namespace app\common\utils\pay;

use app\model\Order;

class Base{

    protected $order;

    protected $account;
    
    /**
     * 初始化支付数据
     * @param \app\model\Order $order 订单数据
     * @param \app\model\Account $account 账户数据
     */
    public function __construct($order='',$account='')
    {
        $this->order=$order;
        $this->account=$account;
    }

    public function getAlipayConfig(){
        $host=request()->domain();
        $config=[
            'publicKey' =>$this->account['config']['pubkey'], //公钥
            'rsaPrivateKey' =>$this->account['config']['private'], //私钥
            'appid' => $this->account['config']['appid'],
            'notify_url' => $host."/paynotice/alipay",
            'return_url' => $host."/payreturn/alipay",
            'charset' => "UTF-8",
            'sign_type'=>"RSA2",
            'gatewayUrl' =>"https://openapi.alipay.com/gateway.do",
        ];
        if(env('app_debug',false)==true){
            $config['gatewayUrl']="https://openapi.alipaydev.com/gateway.do";
        }
        return $config;
    }

    /**
     * 账单回调后处理
     * 支付方式key
     * 订单号
     * 流水号
     * 原始数据
     */
    public function orderHandle($type,$order,$serial,$result){
        /**
         * 修改订单数据信息为待发货
         * 录入相关信息
         */
        $info=Order::where('order_num',$order)->find();
        if($info['state']>0){
            return;
        }
        $info->state=1;
        $info->pay_time=time();
        $info->pay_num=$serial;
        $info->pay_type_key=$type;
        $info->pay_result=json_encode($result,JSON_UNESCAPED_UNICODE);
        $info->save();
    }
}