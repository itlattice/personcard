<?php
namespace app\model;

use iboxs\Model;
use iboxs\model\concern\SoftDelete;

class Goods extends Model
{
    protected $pk="gid";
    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_time';
    protected $updateTime = 'update_time';

    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

    protected $isHeadMap=[
        0=>'自动',1=>'手动'
    ];

    public function goodstype(){
        return $this->belongsTo(GoodsType::class,'gtid','gtid');
    }

    public function getIsHeadAttr($value){
        return $this->isHeadMap[$value];
    }

    public function getStockAttr($value,$data){
        if($data['is_head']==1){
            return $value;
        }
        $gid=$data['gid'];
        if($data['type']==1){
            $card=Card::where(array('gid'=>$gid))->count();
            if($card>0){
                return 999;
            }
            return 0;
        }
        $gpid=GoodsSpecs::where('gid',$gid)->field(['gsid'])->select();
        $gpidArr=[];
        foreach($gpid as $id){
            $gpidArr[]=$id['gsid'];
        }
        $value=Card::where("gid",$gid)->whereIn('gpid',$gpidArr)->where("state",0)->count();
        return $value;
    }

    public static function GetStock($gid,$more=false){
        $where=array("gid"=>$gid);
        if(!$more){
            $where['state']=0;
        }
        $num=Card::where($where)->count();
        return $num;
    }

    public function getScaleAttr($value,$data){
        if($data['is_head']==1){
            return 10;
        }
        $num=Goods::GetStock($data['gid']);   //库存数
        $total=Goods::GetStock($data['gid'],true);  //总量
        if($total<=0){
            $total=0.01;
        }
        $scale=1-$num/$total;
        $scale=round($scale*100,2);
        return $scale;
    }

    public function specs(){
        return $this->hasMany(GoodsSpecs::class,'gid','gid');
    }
}