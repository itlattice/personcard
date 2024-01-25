<?php
namespace app\common;

use app\Base;
use app\common\Cache as CommonCache;
use app\model\Config as ModelConfig;
use iboxs\appauth\Client;
use iboxs\facade\Cache;

class Config{
    public static function set($key,$val){
        $info=ModelConfig::where('key',$key)->find();
        if(!$info){
            $info=new ModelConfig();
            $info->key=$key;
        }
        if(is_array($val)){
            $val=serialize($val);
        }
        $info->value=$val;
        $info->save();
        Cache::rm('webconfig-'.$key);
        Cache::rm('webconfig');
        return true;
    }

    public static function get($key,$default=null){
        $val=ModelConfig::where('key',$key)->value('value');
        if(!$val) return $default;
        if(is_serialized($val)) return unserialize($val);
        return $val;
    }
}