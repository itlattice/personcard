<?php
namespace app\model;

use app\common\utils\email\Email;
use iboxs\facade\Db;
use iboxs\Model;
use iboxs\model\concern\SoftDelete;

class Order extends Model
{
    protected $pk="oid";

    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_time';
    protected $updateTime = false;

    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

    public $stateMap=[
        0=>'待付款',
        1=>'待发货',
        2=>'已发货',
        3=>'交易成功',
        4=>'已退款',
        5=>'订单失效'
    ];

    public function __construct($data=[])
    {
        parent::__construct($data);
        $this->orderHandle();
    }

    public function orderHandle(){
        self::where('add_time','<',time()-(6*3600))->where('state',0)->update([
            'state'=>-1
        ]);
        self::where('pay_time','<',time()-(24*3600))->where('state',2)->update([
            'state'=>3
        ]);
    }

    public function goods(){
        return $this->hasOne('Goods','gid','goods_id');
    }

    public function specs(){
        return $this->hasOne('GoodsSpecs','gsid','gsid');
    }

    public function user(){
        return $this->hasOne(User::class,'uid','uid');
    }

    public function getStateNameAttr(){
        return $this->stateMap[$this->state];
    }

    public function getCardAttr($val){
        if(is_serialized($val)){
            $info=unserialize($val);
            if(is_array($info)){
                return $info;
            }
            return [$info];
        }
        return $val;
    }

    public function getStateInfoAttr(){
        $state=$this->state;
        $color="";
        switch($state){
            case 0:
            case 1:$color='red';break;
            case 2:
            case 3:$color='green';break;
            case 4:
            case 2:$color='red';break;
            default:$color='#000';
        }
        $txt=$this->stateMap[$state];
        return [
            'color'=>$color,
            'txt'=>$txt
        ];
    }

    //订单发货
    public static function DeliverOrder($id){
        $order=self::get($id);
        if(!$order){
            return array(false,'订单不存在');
        }
        Goods::where('gid',$order['goods_id'])->setInc('real_sale',$order['num']);
        $goodsInfo=Goods::get($order['goods_id']);
        if($goodsInfo['is_head']>0){  //手动发货
            //扣减库存
            if($goodsInfo['stock']>$order['num']){
                Goods::where('gid',$order['goods_id'])->setDec('stock',$order['num']);
            } else{
                if($goodsInfo['stock']>0){
                    Goods::where('gid',$order['goods_id'])->setDec('stock',$goodsInfo['stock']);
                }
            }
            Goods::where('stock','<',0)->update(['stock'=>0]);
            return array(true,'订单为手动发货');
        }
        if($order['state']>1){
            return array(false,'订单已发货');
        }
        list($result,$card)=Card::GetOrderCard($order['goods_id'],$order['gsid'],$order['num'],$id);
        if($result==false){
            return array(false,$card);
        }
        return self::dataDeliveryOrder($id,$card);
    }

    public static function dataDeliveryOrder($id,$card){
        $order=self::get($id);
        if(!$order){
            return array(false,'订单不存在');
        }
        $order->card=serialize($card);
        $order->state=2;
        $order->goods_time=time();
        $order->save();
        self::SendInfo($id); //发送短信或邮件信息
        return array(true,$card);
    }

    public static function SendInfo($id){
        $order=self::get($id);
        if($order['is_email']!=''||$order['is_email']!=null){  //发送邮件
            Email::sendOrderEmail($order['is_email'],$order['card'],$order);
        }
        if($order['is_sms']!=''||$order['is_sms']!=null){  //发送短信

        }
    }

    //订单退款
    public static function RefundOrder($id){
        $order=self::get($id);
        if(!$order){
            return array(false,'订单退款失败');
        }
        $order->state=4;
        $order->save();

        //退款数据

        return array(true,'');
    }

    public function getGoodsTimeAttr($val){
        return date("Y-m-d H:i:s",$val);
    }

    public function getPayTimeAttr($val){
        if($val==null) return null;
        return date("Y-m-d H:i:s",$val);
    }
}