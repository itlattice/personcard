<?php
namespace app\common\utils\pay;

use app\Base as appBase;
use iboxs\payment\Client;
use iboxs\payment\Notify;

class Wechat extends Base{
    /**
     * 启动微信支付
     */
    public function Main(){
        $host=request()->domain();
        $config=[
            'mchid'=>$this->account['config']['merchantid'],
            'appid'=>$this->account['config']['appid'],
            'apiKey'=>$this->account['config']['key'],
            'notify_url' => $host."/paynotice/wechat",
            'return_url' => $host."/payreturn/wechat",
        ];
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
        if(request()->isMobile()){
            return $client->WxPayWap($orderInfo);
        } else{
            $codeInfo=$client->WxPayCode($orderInfo);
            return $codeInfo['code_url']??die('配置异常，请联系管理员');
        }
    }

    public function Notify(){
        $host=request()->domain();
        $config=[
            'mchid'=>$this->account['config']['merchantid'],
            'appid'=>$this->account['config']['appid'],
            'apiKey'=>$this->account['config']['key'],
            'notify_url' => $host."/paynotice/wechat",
            'return_url' => $host."/payreturn/wechat",
        ];
        $result=Notify::WxPayNotify($config);
        if($result==false){
            return false;
        }
        $order=$result['out_trade_no'];
        $serial=$result['transaction_id'];
        $this->orderHandle('wechat',$order,$serial,$result);
        return $order;
    }
}