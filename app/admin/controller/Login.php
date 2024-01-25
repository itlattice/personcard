<?php
namespace app\admin\controller;

use app\Base;
use app\common\Cookie;
use app\model\Admin;
use iboxs\captcha\Captcha;

class Login extends Base{
    public function login(){
        return $this->fetch();
    }

    public function loginpost(){
        $data=request()->post();
        if(!captcha_check($data['code'])){
            return $this->json(-4,'验证码错误');
        }
        $user=Admin::where('username',$data['username'])->find();
        if(!$user){
            return $this->json(-7,'用户名或密码错误');
        }
        $salt=$user['salt'];
        $password=md5($data['password'].$salt);
        if($password!=$user['password']){
            return $this->json(-7,'用户名或密码错误');
        }
        Cookie::GetAdminCookie($user['aid']);
        return $this->json(0,'登录成功');
    }
}