<?php
namespace app\index\controller;

use app\Base;
use app\common\utils\email\Email;
use app\common\utils\pay\Alipay;
use app\common\utils\pay\CodePay;
use app\common\utils\pay\Epay;
use app\common\utils\pay\Wechat;
use app\index\BaseController;
use app\model\Account;
use app\model\Order;
use iboxs\facade\Cookie;

class Pay extends BaseController
{
    /**
     * 支付结果异步通知
     */
    public function Notice($key){
        if(env('app_debug',false)==true){
            file_put_contents(LOG_PATH.'paylog.log',json_encode(['key'=>$key,'data'=>(request()->param())]).PHP_EOL,FILE_APPEND);
        }
        $class=[];
        $account=Account::where('key',$key)->find();
        switch($key){
            case 'alipay':$class=new Alipay([],$account);break;
            case 'wechat':$class=new Wechat([],$account);break;
            case 'codepay':$class=new CodePay([],$account);break;
            case 'epay':$class=new Epay([],$account);break;
        }
        $ordernum=$class->Notify();
        if($ordernum==false){  //验签未通过
            return;
        }
        $orderinfo=Order::where('order_num',$ordernum)->with(['goods','specs'])->find();
        if(!$orderinfo){
            return;
        }
        Order::DeliverOrder($orderinfo['oid']);
        $email=Base::getSysConfig()['email']['admin'];
        if($email>0){
            $this->sendAdminEmail($orderinfo);
        }
    }

    public function sendAdminEmail($orderInfo){
        $user=Email::getEmailUser();
        if($user['smtp']==''){
            return;
        }
        $config=Base::getWebConfig();
        $subject=($config['title']??'').'有新订单';
        $tmp='订单编号：'.$orderInfo['order_num'].'<br/>';
        $tmp.='商品：'.$orderInfo['goods']['name']??'商品信息错误';
        $tmp.='规格：'.$orderInfo['specs']['name']??'商品信息错误';
        $tmp.='数量：'.$orderInfo['num']??1;
        $email=Base::getSysConfig()['admin']['email'];
        if($email==''){
            return;
        }
        Email::SendEmail($user,$subject,$email,$tmp);
    }

    /**
     * 同步跳转
     */
    public function Results($key){
        $order=request()->param('out_trade_no','');
        if($order==''){
            $order=Cookie::get('payorder');
            if($order==null){
                return '订单未查到，请使用系统订单查询进行查找';
            }
        }
        $orderInfo=Order::where('order_num',$order)->find();
        if(!$orderInfo){
            return '订单不存在';
        }
        return redirect("/orderresult/{$order}.html")->send();
    }

    public function PayState(){
        $order=request()->post('order','');
        $state=Order::where('order_num',$order)->value('state');
        if(!$state){
            return $this->json(0,'未支付1');
        }
        if($state>0){
            return $this->json(1,'已支付');
        }
        return $this->json(0,'未支付');
    }
}