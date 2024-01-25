<?php
namespace app\model;

use iboxs\Model;

class Account extends Model
{
    protected $pk='acid';

    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_time';
    protected $updateTime = 'update_time';

    public function getConfigAttr($value,$data){
        return unserialize($data['data']);
    }
}