<?php
namespace app\admin\controller;

use app\admin\BaseController;
use app\model\Admin;
use iboxs\facade\Session;

class Info extends BaseController{
    public function updatepwd(){
        return $this->fetch();
    }

    public function loginout(){
        Session::delete('aid');
        Session::delete('admininfo');
        Session::delete('browserinfo');
        return redirect('/admin/');
    }

    public function udpwd(){
        $pwd=request()->post('pwd');
        $old=request()->post('old');
        $user=Admin::get($this->aid);
        $salt=$user['salt'];
        if(md5($old.$salt)!=$user['password']){
            return $this->json(-6,'旧密码错误');
        }
        $salt=GetRandStr(4);
        $pwd=md5($pwd.$salt);
        $user->password=$pwd;
        $user->salt=$salt;
        $user->save();
        return $this->json(0,'修改成功');
    }
}