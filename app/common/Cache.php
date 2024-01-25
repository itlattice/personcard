<?php
namespace app\common;

use iboxs\facade\Cache as FacadeCache;

class Cache{
    /**
     * 读取缓存
     */
    public static function get($key,$default=null){
        $cache=FacadeCache::get($key,$default);
        if($cache==$default){
            return $default;
        }
        if(is_serialized($cache)){
            return unserialize($cache);
        }
        return $cache;
    }

    /**
     * 写入缓存
     */
    public static function set($key,$value,$expire=0){
        if(is_array($value)){
            $value=serialize($value);
        }
        return FacadeCache::set($key,$value,$expire);
    }

    public static function delete($key){
        return FacadeCache::rm($key);
    }
}