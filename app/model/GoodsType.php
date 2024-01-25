<?php
namespace app\model;

use iboxs\Model;
use iboxs\model\concern\SoftDelete;

class GoodsType extends Model
{
    protected $pk="gtid";

    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_time';
    protected $updateTime = false;

    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;


    public static $stateMap=[
        0 => '隐藏',
        1 => "显示"
    ];

    public static $goodsSortMap=[
        0 => '默认排序',
        1 => '添加时间降序',
        2 => '添加时间升序',
        3 => '商品价格降序',
        4 => '商品价格升序',
        5 => '商品销量降序',
        6 => '商品销量升序'
    ];

    public function getStateAttr($value){
        return self::$stateMap[$value];
    }

    public function getGoodsSortAttr($value){
        return self::$goodsSortMap[$value];
    }

    public function getCountAttr($value,$data){
        return Goods::where("gtid",$data['gtid'])->count();
    }
}