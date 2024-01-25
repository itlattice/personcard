<?php
namespace app\common;

use app\model\Card;
use app\model\Goods as ModelGoods;
use app\model\GoodsPrice;
use app\model\UserGroup;

class Goods{
    public static function getGoodsPriceList($ugid,$id){
        $min=GoodsPrice::where(array(
            'ugid'=>$ugid,
            'gid'=>$id
        ))->min('sale_price');
        if(!$min){
            $ugid=UserGroup::GetMoreGroup();
            $min=GoodsPrice::where(array(
                'ugid'=>$ugid,
                'gid'=>$id
            ))->min('sale_price');
            if(!$min){
                $min=GoodsPrice::where(array(
                    'gid'=>$id
                ))->min('sale_price');
            }
        }
        /*************************/
        $max=GoodsPrice::where(array(
            'ugid'=>$ugid,
            'gid'=>$id
        ))->max('sale_price');
        if(!$max){
            $ugid=UserGroup::GetMoreGroup();
            $max=GoodsPrice::where(array(
                'ugid'=>$ugid,
                'gid'=>$id
            ))->max('sale_price');
            if(!$max){
                $max=GoodsPrice::where(array(
                    'gid'=>$id
                ))->max('sale_price');
            }
        }
        $min=sprintf('%.2f',$min);
        $max=sprintf('%.2f',$max);
        if($min==$max){
            return $min;
        }
        return "{$min}-{$max}";
    }

    public static function getGoodsFromPriceList($ugid,$id){
        $min=GoodsPrice::where(array(
            'ugid'=>$ugid,
            'gid'=>$id
        ))->min('price');
        if(!$min){
            $ugid=UserGroup::GetMoreGroup();
            $min=GoodsPrice::where(array(
                'ugid'=>$ugid,
                'gid'=>$id
            ))->min('price');
            if(!$min){
                $min=GoodsPrice::where(array(
                    'gid'=>$id
                ))->min('price');
            }
        }
        /*************************/
        $max=GoodsPrice::where(array(
            'ugid'=>$ugid,
            'gid'=>$id
        ))->max('price');
        if(!$max){
            $ugid=UserGroup::GetMoreGroup();
            $max=GoodsPrice::where(array(
                'ugid'=>$ugid,
                'gid'=>$id
            ))->max('price');
            if(!$max){
                $max=GoodsPrice::where(array(
                    'gid'=>$id
                ))->max('price');
            }
        }
        $min=sprintf('%.2f',$min);
        $max=sprintf('%.2f',$max);
        if($min==$max){
            return $min;
        }
        return "{$min}-{$max}";
    }

    /**
     * 计算价格数据信息
     */
    public static function getGoodsPrice($gid,$specs,$ugid,$num=1){
        if($ugid==0){
            $ugid=UserGroup::GetMoreGroup();
        }
        $priceInfo=GoodsPrice::where(array('gid'=>$gid,'gsid'=>$specs,'ugid'=>$ugid))->find();
        if(!$priceInfo){
            $priceInfo=GoodsPrice::where(array('gid'=>$gid,'gsid'=>$specs))->order('sale_price','DESC')->find();
        }
        $form=$priceInfo['price'];  //原价
        $unit=$priceInfo['cost']; //成本价
        $sale=$priceInfo['sale_price'];  //售价
        $stock=self::getGoodsSpecsStock($gid,$specs); //获取商品该规格的库存
        return array($form*$num,$unit*$num,$sale*$num,$stock);
    }

    public static function getGoodsSpecsStock($gid,$gsid){
        $data=ModelGoods::get($gid);
        if($data['is_head']==1){
            return $data['stock'];
        }
        if($data['type']==1){
            $count=Card::where(array("gid"=>$gid,'gpid'=>$gsid))->count();
            if($count>0){
                return 999;
            }
            return 0;
        }
        $gid=$data['gid'];
        $value=Card::where(array("gid"=>$gid,'gpid'=>$gsid))->where("state",0)->count();
        return $value;
    }

    /**
     * 计算订单价格
     */
    public static function getGoodsSalePrice($ugid,$goods_id,$specs,$goods_num){
        if($ugid==0){
            $ugid=UserGroup::GetMoreGroup();
        }
        list($form,$unit,$sale,$stock)=self::getGoodsPrice($goods_id,$specs,$ugid,$goods_num);
        if($stock<$goods_num){
            return false;
        }
        return $sale;
    }
}