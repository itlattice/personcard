<?php
namespace app\common\utils\pay;

use app\Base as appBase;
use iboxs\payment\Client;
use iboxs\payment\Notify;

class AlipayCode extends Base{
    /**
     * 启动支付宝支付
     */
    public function Main(){
        $config=$this->getAlipayConfig();
        $sysConfig=appBase::getSysConfig();
        $name=$this->order['goods']['name'];
        if($sysConfig['order']['name']!=''){
            $name=$sysConfig['order']['name'];
        }
        $orderInfo=array(
            'order_name'=>$name,
            'amount'=>$this->order['info_price'],
            'out_trade_no'=>$this->order['order_num']
        );
        $client=new Client($config);
        $codeInfo=$client->AlipayCode($orderInfo);
        return $codeInfo['qr_code']??die($codeInfo['msg']);
    }

    public function Notify(){
        $config=$this->getAlipayConfig();
        $result=Notify::alipayNotify($config);
        if($result==false){
            return false;
        }
        $order=$result['out_trade_no'];
        $serial=$result['trade_no'];
        $this->orderHandle('alipaycode',$order,$serial,$result);
        return $order;
    }
}