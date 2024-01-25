<?php
namespace app\model;

use iboxs\Model;
use iboxs\model\concern\SoftDelete;

class GoodsSpecs extends Model
{
    protected $pk="gsid";

    protected $autoWriteTimestamp = true;
    protected $createTime = false;
    protected $updateTime = 'update_time';

    public function price(){
        return $this->hasMany(GoodsPrice::class,'gsid','gsid');
    }
}