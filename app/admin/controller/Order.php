<?php
namespace app\admin\controller;

use app\admin\BaseController;
use app\model\Order as ModelOrder;

class Order extends BaseController{
    public function index(){
        return $this->fetch();
    }

    public function sail(){
        return $this->fetch();
    }

    public function list(){
        $keyword=request()->post('keyword','');
        $state=request()->post('state',-1);
        $order=ModelOrder::order('oid')->with(['user','goods','specs']);
        if($keyword!=''){
            $order=$order->where('order_num|pay_num|account','like',"%{$order}%");
        }
        if($state>-1){
            $order=$order->where('state',$state);
        } else{
            $order=$order->where('state','>',0);
        }
        return $this->layJson($order);
    }

    public function refoudorder(){
        $id=request()->param('id');
        ModelOrder::RefundOrder($id);
        return $this->json(0,'退款成功');
    }

    public function saillist(){
        $keyword=request()->post('keyword','');
        $order=ModelOrder::where('state',1)->order('oid')->with(['user','goods','specs']);
        if($keyword!=''){
            $order=$order->where('order_num|pay_num|account','like',"%{$order}%");
        }
        return $this->layJson($order);
    }

    public function ordersale(){
        $id=request()->param('id');
        if(request()->isGet()){
            $order=ModelOrder::with(['user','goods','specs'])->where('oid',$id)->find();
            $this->assign('info',$order);
            return $this->fetch();
        }
        $card=request()->post('card');
        ModelOrder::dataDeliveryOrder($id,$card);
        return $this->json(0,'发货成功');
    }

    public function delorder(){
        $id=request()->param('id');
        $order=ModelOrder::get($id);
        if($order){
            $order->delete();
        }
        return $this->json(0,'删除成功');
    }
}