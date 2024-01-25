<?php
namespace app\model;

use iboxs\Model;

class Card extends Model
{
    protected $pk="cid";
    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_time';
    protected $updateTime = false;

    public $stateMap=[
        0=>'正常',
        1=>'已售出',
        2=>'已核销'
    ];

    public function goods(){
        return $this->hasOne("Goods","gid","gid");
    }

    public function specs(){
        if($this->gpid==null){
            return "";
        }
        return $this->hasOne("GoodsSpecs","gsid","gpid");
    }

    public function order(){
        return $this->hasOne("Order","oid","oid");
    }

    public function getStateAttr($value){
        return $this->stateMap[$value];
    }

    public static function GetOrderCard($goods_id,$specs_id,$num=1,$order_id=0){
        $goods_type=Goods::where("gid",$goods_id)->value("type");
        if($goods_type==0){  //卡密
            return self::getCard($goods_id,$specs_id,$num,$order_id);
        } elseif($goods_type==1){  // 链接
            return self::getUrl($goods_id,$specs_id);
        } elseif($goods_type==2) {  // 租号
            return self::getAccount($goods_id,$specs_id);
        } else{
            return false;
        }
    }

    private static function getAccount($goods_id,$specs_id){

    }

    public static function getUrl($goods_id,$specs_id){
        $card=self::where(array(
            'gid'=>$goods_id,
            'gpid'=>$specs_id
        ))->order('cid','ASC')->find();
        $url=$card['card'];
        return array(true,[$url]);
    }

    private static function getCard($goods_id,$specs_id,$num=1,$order_id=0){
        $card=self::where(array(
            'gid'=>$goods_id,
            'gpid'=>$specs_id,
            'state'=>0
        ))->order('cid','ASC')->limit($num)->select();
        if(!$card){
            return array(false,'卡密库存不足');
        }
        $stock=count($card->toArray());
        if($stock<$num){
            return array(false,"卡密库存不足，仅剩{$stock}");
        }
        $cardArr=array();
        $cardIdArr=array();
        foreach($card as $k=>$v){
            $cardArr[]='卡号：'.$v['card'].'|卡密：'.$v['pwd'];
            $cardIdArr[]=$v['cid'];
        }
        if(count($cardArr)<$num){
            return array(false,"卡密库存不足，仅剩".count($cardArr));
        }
        Card::whereIn('cid',$cardIdArr)->update(array(
            'state'=>1,
            'sale_time'=>time(),
            'oid'=>$order_id
        ));
        return array(true,$cardArr);
    }

    public function getSaleTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }
}