<?php
namespace app\index\controller;

use app\Base;
use app\common\Config;
use app\common\GetNum;
use app\common\Goods as CommonGoods;
use app\common\Pay;
use app\index\BaseController;
use app\model\Account;
use app\model\Goods;
use app\model\Order as ModelOrder;
use iboxs\facade\Cookie;

class Order extends BaseController
{
    public function Index(){
        $type=request()->get('type','myorder');
        $this->assign('type',$type);
        $this->assign('uid',$this->uid);
        return $this->fetch();
    }

    public function delorder(){
        $id=request()->post('id');
        ModelOrder::where('oid',$id)->update([
            'user_delete_time'=>time()
        ]);
        return $this->json(0,'删除成功');
    }

    public function orderview(){
        $id=request()->get('id');
        $item=ModelOrder::where('oid',$id)->with(['goods','specs'])->find();
        switch($item['state']){
            case 0:$item['statetxt']='待付款';$item['statecolor']='red';break;
            case 1:$item['statetxt']='待发货';$item['statecolor']='red';break;
            case 2:$item['statetxt']='已发货';$item['statecolor']='green';break;
            case 3:$item['statetxt']='交易成功';$item['statecolor']='green';break;
            case 4:$item['statetxt']='已退款';$item['statecolor']='gray';break;
            case 5:$item['statetxt']='订单失效';$item['statecolor']='gray';break;
        }
        $this->assign('info',$item);
        return $this->fetch();
    }

    public function shouhuo(){
        $id=request()->post('id');
        ModelOrder::where('oid',$id)->update([
            'state'=>3
        ]);
        return $this->json(0,'交易成功');
    }

    public function orderResult($order){
        return redirect('/order.html');
    }

    public function cardView(){
        $id=request()->get('id');
        $item=ModelOrder::with(['specs','goods'])->get($id);
        switch($item['state']){
            case 0:$item['statetxt']='待付款';$item['statecolor']='red';break;
            case 1:$item['statetxt']='待发货';$item['statecolor']='red';break;
            case 2:$item['statetxt']='已发货';$item['statecolor']='green';break;
            case 3:$item['statetxt']='交易成功';$item['statecolor']='green';break;
            case 4:$item['statetxt']='已退款';$item['statecolor']='gray';break;
            case 5:$item['statetxt']='订单失效';$item['statecolor']='gray';break;
        }
        $this->assign('info',$item);
        return $this->fetch();
    }

    public function list(){
        $offset=request()->post('offset',0);
        $limit=request()->post('limit',10);
        $type=request()->post('type','myorder');
        $order=ModelOrder::where('add_time','>',90*86400)->where('user_delete_time',0);
        switch($type){
            case 'myorder':$order=$this->MyOrder($order);break;
            case 'orderno':$order=$this->OrderNo($order);break;
            case 'voucher':$order=$this->Voucher($order);break;
        }
        if($order===false){
            return $this->json(-4,'验证码错误');
        }
        $count=$order->count();
        $order=$order->limit($offset,$limit)->order('oid','DESC')->with(['goods','specs'])->select()->map(function($item){
            switch($item['state']){
                case 0:$item['statetxt']='待付款';$item['statecolor']='red';break;
                case 1:$item['statetxt']='待发货';$item['statecolor']='red';break;
                case 2:$item['statetxt']='已发货';$item['statecolor']='green';break;
                case 3:$item['statetxt']='交易成功';$item['statecolor']='green';break;
                case 4:$item['statetxt']='已退款';$item['statecolor']='gray';break;
                case 5:$item['statetxt']='订单失效';$item['statecolor']='gray';break;
            }
            return $item;
        });
        return $this->json(0,'获取成功',$order,['count'=>$count]);
    }

    public function MyOrder($order){
        $uid=$this->uid;
        if($uid==0){  //浏览器缓存
            $cookie=GetNum::GetOrderCookie();
            $order=$order->where('cookie',$cookie);
        } else{  //我的订单
            $cookie=GetNum::GetOrderCookie();
            $order=$order->where('uid',$this->uid);
        }
        return $order;
    }

    public function OrderNo($order){
        $code=request()->post('code');
        if(!captcha_check($code)){
            return false;
        }
        $num=request()->post('orderno');
        $order=$order->where('order_num',$num);
        return $order;
    }

    public function Voucher($order){
        $code=request()->post('code');
        if(!captcha_check($code)){
            return false;
        }
        $account=request()->post('account');
        $pwd=request()->post('password');
        if($account==null && $pwd!=null){  //仅开启了查单密码
            $order=$order->where(array('account'=>null,'pwd'=>$pwd));
        } else if($pwd==null && $account!=null){ //仅开启了查单账号
            $order=$order->where(array('pwd'=>null,'account'=>$pwd));
        } else{
            $order=$order->where(array('pwd'=>$pwd,'account'=>$pwd));
        }
        return $order;
    }

    /**
     * 提交订单
     */
    public function submitorder(){
        /**
         * 检查是否需要登录
         * 检查库存情况
         * 计算价格
         * 写入订单
         * 返回订单号
         */
        /********************************************/
        $array=request()->post();
        $sysconfig=Base::getSysConfig();
        if($sysconfig['webOn']['youke']<1){
            return $this->json(-403,'请先登录',[],['url'=>'/login']);
        }

        $goods=Goods::get($array['goods_id']);  //
        if($this->uid<1 && $goods['is_login']>0){
            return $this->json(-403,'请先登录',[],['url'=>'/login']);
        }
        if($goods['is_pwd']>0){  //需要购买密码
            $shopPwd=request()->post('shoppassword','');
            if($shopPwd==''){
                return $this->json(-403,'购买密码错误');
            }
            if($shopPwd!=$goods['pwd']){
                return $this->json(-403,'购买密码错误');
            }
        }
        $price=CommonGoods::getGoodsSalePrice($this->userinfo['gid']??0,$array['goods_id'],$array['specs'],$array['goods_num']);
        if($price===false){
            return $this->json(-404,'库存不足');
        }
        $isEmail=0;
        if(($array['emailcheck']??'off')=='on'){
            $isEmail=$array['email']??null;
            if($isEmail==null){
                return $this->json(-403,'请填写邮箱');
            }
        }
        $goodsInfo=Goods::get($array['goods_id']);
        $cookie=GetNum::GetOrderCookie();
        $ordernum=GetNum::CreateOrderNum();
        $order=[
            'order_num'=>$ordernum,
            'uid'=>$this->uid,
            'goods_id'=>$array['goods_id'],
            'gsid'=>$array['specs'],
            'num'=>$array['goods_num'],
            'is_email'=>$isEmail,
            'price'=>$price,
            'info_price'=>$price,
            'is_head'=>$goodsInfo['is_head']=='自动'?0:1,
            'account'=>$array['account']??null,
            'pwd'=>$array['password']??null,
            'cookie'=>$cookie,
            'sale_price'=>$price/$array['goods_num']
        ];
        $orderData=new ModelOrder($order);
        $orderData->save();
        return $this->json(0,'下单成功',[],['url'=>"/order/{$ordernum}.html"]);
    }

    public function View($ordernum){
        $orderNum=ModelOrder::where('order_num',$ordernum)->with(['goods','specs'])->find();
        if(!$orderNum){
            return '订单不存在';
        }
        if($orderNum['state']>0){
            return '订单已支付';
        }
        $this->assign('info',$orderNum);
        $payAccount=[];
        $mobile=request()->isMobile();
        $sb=$mobile==true?'wap':'pc';
        $alipay=Account::where(array('on'=>1,'alipay'=>1,$sb=>1,'config'=>1))->order('sort')->find();
        $paytype='';
        if(!$alipay){
            $payAccount['alipay']=false;
        } else{
            $payAccount['alipay']=$alipay['key'];
            $paytype=$alipay['key'].'-alipay';
        }
        /*****************************************************/
        $wechat=Account::where(array('on'=>1,'wechat'=>1,$sb=>1,'config'=>1))->order('sort')->find();
        if(!$wechat){
            $payAccount['wechat']=false;
        } else{
            $payAccount['wechat']=$wechat['key'];
            if($paytype==''){
                $paytype=$wechat['key'].'-wechat';
            }
        }
        $this->assign('paytype',$paytype);
        $this->assign('payAccount',$payAccount);
        return $this->fetch();
    }

    public function Paytype(){
        // <input name="pay_type" value="{$paytype}" type="hidden" />
        //                 <input type="hidden" name="ordernum" value="{$info.order_num}">
        $ordernum=request()->post('ordernum','');
        $paytype=request()->post('pay_type','');
        if($ordernum==''||$paytype==''){
            return '非法提交，请返回重新下单';
        }
        $order=ModelOrder::where('order_num',$ordernum)->with(['goods'])->find();
        if(!$order){
            return '订单不存在';
        }
        if($order['state']>0){
            return '订单已支付';
        }
        $payArr=explode('-',$paytype);
        if($payArr[1]=='alipay'){
            $paytypeInfo=0;
        } else if($payArr[1]=='wechat'){
            $paytypeInfo=1;
        } else{
            return '支付方式错误';
        }
        $mobile=request()->isMobile();
        $sb=$mobile==true?'wap':'pc';
        $account=Account::where(array('key'=>$payArr[0],'on'=>1,$sb=>1,'config'=>1))->find();
        if(!$account){
            return '支付方式异常';
        }
        $order->pay_type=$paytypeInfo;
        $order->acid=$account['acid'];
        $order->save();
        Cookie::set('payorder',$ordernum,600);
        /*********************************************/
        /**
         * 根据支付账户情况发起支付
         */
        if($order['info_price']<0.01){
            ModelOrder::DeliverOrder($order['oid']);
            return redirect('/order.html');
        }
        $result= Pay::startPay($order,$account);
        if($result==false){
            return;
        }
        $code=$result;
        $this->assign('order',$order);
        $this->assign('code',$code);
        $this->assign('paytype',$paytypeInfo);
        $time=500;
        $this->assign('time',$time);
        $key=$account['key'];
        if($key=='alipaycode'){
            $key='alipay';
        }
        $this->assign('key',$key);
        if($paytypeInfo==0){  //支付宝支付
            return $this->fetch('order/startpay');
        } else if($paytypeInfo==1){ //微信支付
            return $this->fetch('order/startpay');
        }
    }
}