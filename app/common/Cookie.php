<?php
namespace app\common;

use app\model\Admin;
use app\model\User;
use iboxs\facade\Session;

class Cookie{
    /**
     * 管理后台的cookie授予
     */
    public static function GetAdminCookie($aid){
        Session::set('aid',$aid);
        $info=Admin::getInfo($aid);
        Session::set('admininfo',$info);
        Session::set('browserinfo',$_SERVER);
        return $info;
    }

    /**
     * 管理员cookie校验
     */
    public static function CheckAdminCookie(){
        $aid=Session::get('aid');
        if($aid==null) {
            return false;
        }
        $info=Session::get('admininfo');
        if($info==null) {
            return false;
        };
        return array($aid,$info);
    }

    /**
     * 用户cookie校验
     */
    public static function CheckCookie(){
        $uid=Session::get('uid');
        if($uid==null) {
            return false;
        }
        $info=Session::get('userinfo');
        if($info==null) {
            return false;
        };
        return array($uid,$info);
    }

    /**
     * 用户cookie授予
     */
    public static function GetUserCookie($uid){
        Session::set('uid',$uid);
        $info=User::getInfo($uid);
        Session::set('userinfo',$info);
        Session::set('browserinfo',$_SERVER);
        return $info;
    }
}