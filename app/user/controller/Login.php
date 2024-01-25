<?php
namespace app\user\controller;

use app\Base;
use app\common\Config;
use app\common\Cookie;
use app\model\User;
use app\model\UserGroup;
use app\user\BaseLoginController;
use Pl1998\ThirdpartyOauth\SocialiteAuth;

class Login extends BaseLoginController{
    public function index(){
        return $this->fetch();
    }

    public function reg(){
        return $this->fetch();
    }
    
    public function QQLogin(){
        $config=$this->QQConfig();
        $api = new SocialiteAuth($config);
        $json = $api->redirect('qq');
        var_dump($json);
    }

    private function QQConfig(){
        $url=request()->domain().'/oauthqq';
        $sysConfig=Base::getSysConfig();
        return [
            'client_id' => $sysConfig['qqlogin']['clientid'],
            'redirect_uri' => $url,
            'client_secret'=>$sysConfig['qqlogin']['secret']
        ];
    }

    public function oauthqq(){
        $config=$this->QQConfig();
        $api = new SocialiteAuth($config);
        $userinfo = $api->driver('qq')->user();
        $openid=$userinfo->openid;
        $user=User::where('qqopenid',$openid)->find();
        if(!$user){
            $user=new User();
            $user->username=$userinfo->nickname??GetRandStr(6);
            $salt=GetRandStr(4);
            $pwd=GetRandStr(6);
            $pwd=md5($pwd.$salt);
            $user->password=$pwd;
            $user->salt=$salt;
            $user->nickname='用户'.GetRandStr(4);
            $user->gid=UserGroup::GetMoreGroup();
            $user->login_time=time();
            $user->qqopenid=$openid;
            $user->qqinfo=serialize($userinfo);
            $user->save();
        }
        $uid=$user->uid;
        Cookie::GetUserCookie($uid);
        return redirect('/');
    }

    public function reghandle(){
        $code=request()->post('code');
        $usr=request()->post('login');
        $pwd=request()->post('pwd');
        $email=request()->post('email');
        
        if(!(isEmail($email)||isPhone($email))){
            return $this->json(-4,'请输入合法的邮箱或手机号');
        }
        if(!captcha_check($code)){
            return $this->json(-6,'验证码错误');
        }
        $counta=User::where('username',$usr)->count();
        $countb=User::where('telephone',$email)->count();
        $countc=User::where('email',$email)->count();
        if(($counta+$countb+$countc)>0){
            return $this->json(-7,'信息已被注册');
        }
        $user=new User();
        $user->username=$usr;
        $salt=GetRandStr(4);
        $pwd=md5($pwd.$salt);
        $user->password=$pwd;
        $user->salt=$salt;
        $user->nickname='用户'.GetRandStr(4);
        $user->gid=UserGroup::GetMoreGroup();
        if(isPhone($email)){
            $user->telephone=$email;
        }
        if(isEmail($email)){
            $user->email=$email;
        }
        $user->login_time=time();
        $user->save();
        $uid=$user->uid;
        Cookie::GetUserCookie($uid);
        return $this->json(0,'注册成功');
    }

    public function handle(){
        $code=request()->post('code');
        $usr=request()->post('login');
        $pwd=request()->post('pwd');
        if(!captcha_check($code)){
            return $this->json(-4,'验证码错误');
        }
        $user=User::where('username|telephone|email',$usr)->find();
        if(!$user){
            return $this->json(-403,'用户名或密码错误');
        }
        $salt=$user['salt'];
        $pwd=md5($pwd.$salt);
        if($user['password']!=$pwd){
            return $this->json(-403,'用户名或密码错误');
        }
        Cookie::GetUserCookie($user['uid']);
        $user->login_time=time();
        $user->save();
        return $this->json(0,'登录成功');
    }
}