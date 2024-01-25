<?php
namespace app\model;

use iboxs\Model;
use iboxs\model\concern\SoftDelete;

class User extends Model
{
    protected $pk="uid";

    protected $autoWriteTimestamp = true;
    protected $createTime="reg_time";
    protected $updateTime=false;

    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

    protected static $stateMap=[
        0=>'正常',
        1=>'封禁'
    ];

    public function group(){
        return $this->belongsTo("UserGroup","gid","gid");
    }

    public function getStateAttr($value){
        return self::$stateMap[$value];
    }

    public function getLoginTimeAttr($value){
        return date("Y-m-d H:i",$value);
    }

    public static function getInfo($uid){
        return self::get($uid);
    }
}