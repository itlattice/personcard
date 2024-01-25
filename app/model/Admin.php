<?php
namespace app\model;

use iboxs\Model;

class Admin extends Model
{
    protected $pk="aid";

    protected $autoWriteTimestamp = false;

    public static function getInfo($aid){
        $info=self::get($aid);
        return $info;
    }
}