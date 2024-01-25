<?php
namespace app\model;

use iboxs\Model;
use iboxs\model\concern\SoftDelete;

class GoodsPrice extends Model
{
    protected $pk="gpid";

    protected $autoWriteTimestamp = false;

    /**
     * 获取商品售价
     * 商品ID、规格ID、用户组ID
     */
    public static function GetGoodsPrice($gid,$gsid,$ugid){
        $price=self::where(array(
            'gid'=>$gid,
            'gsid'=>$gsid,
            'ugid'=>$ugid
        ))->find();
        return $price;
    }
}