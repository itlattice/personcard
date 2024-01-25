<?php
namespace app\common\utils\pay;
use app\Base as AppBase;
use iboxs\payment\Client;
use iboxs\payment\Notify;

class Epay extends Base{
    /**
     * 启动易支付
     */
    public function Main(){
        $type='alipay';
        if($this->order['pay_type']==1){
            $type='wxpay';
        }
        $name=$this->order['goods']['name'];
        $sysConfig=appBase::getSysConfig();
        if($sysConfig['order']['name']!=''){
            $name=$sysConfig['order']['name'];
        }
        $orderInfo=[
            'type'=>$type, //支付方式（alipay:支付宝,qqpay:QQ钱包,wxpay:微信支付）
            'order'=>$this->order['order_num'],
            'name'=>$name,
            'amount'=>$this->order['info_price']
        ];
        $config=$this->getConfig();
        $client=new Client($config);
        $result=$client->chEWeb($orderInfo);
        return false;
    }

    public function Notify(){
        $host=request()->domain();
        $config=[
            'merid'=>$this->account['config']['merid'],
            'return_url'=>$host.'/payreturn/codepay',
            'notify_url'=>$host.'/paynotice/codepay',
            'sitename'=>AppBase::getWebConfig()['title'],
            'key'=>$this->account['config']['key']
        ];
        $result=Notify::chPayNotify($config);
        if($result==false){
            return false;
        }
        $order=$result['out_trade_no'];
        $serial=$result['trade_no'];
        $this->orderHandle('epay',$order,$serial,$result);
        return $order;
    }

    private function getConfig(){
        $host=request()->domain();
        $config=[
            'merid'=>$this->account['config']['merid'],
            'notify_url'=>$host.'/paynotice/epay',
            'return_url'=>$host.'/payreturn/epay',
            'key'=>$this->account['config']['key']
        ];
        return $config;
    }
}