<?php
namespace app\model;

use iboxs\Model;

class UserGroup extends Model
{
    protected $pk="gid";

    protected $autoWriteTimestamp = true;
    protected $createTime="add_time";
    protected $updateTime=false;

    public static function GetMoreGroup(){
        return self::where(array('is_more'=>1))->value('gid');
    }
}