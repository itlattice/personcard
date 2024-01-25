<?php
namespace app\admin\controller;

use app\admin\BaseController;
use app\common\Cache;
use app\common\Config as CommonConfig;
use app\common\utils\email\Email;
use app\model\Account;
use app\model\Config as ModelConfig;
use app\model\UserGroup;

class Config extends BaseController{
    public function index(){
        $info=ModelConfig::where('coid','>',0)->select();
        foreach($info as $k=>$v){
            $key=$v['key'];
            $val=$v['value'];
            if(is_serialized($val)){
                $val=unserialize($val);
            }
            $this->assign($key,$val);
        }
        return $this->fetch();
    }

    public function submit(){
        $webconfig=request()->post('webConfig');
        // $webconfig['logo']='';
        CommonConfig::set('webConfig',$webconfig);
        CommonConfig::set('webOn',request()->post('webOn'));
        CommonConfig::set('order',request()->post('order'));
        CommonConfig::set('email',request()->post('email'));
        CommonConfig::set('indexwindows',request()->post('indexwindows'));
        CommonConfig::set('goodswindows',request()->post('goodswindows'));
        CommonConfig::set('notice',request()->post('notice'));
        CommonConfig::set('admin',request()->post('admin'));
        CommonConfig::set('orderemail',request()->post('orderemail'));
        CommonConfig::set('orderemailsubject',request()->post('orderemailsubject'));
        CommonConfig::set('qqlogin',request()->post('qqlogin'));
        Cache::delete('sysconfig');
        return $this->json();
    }

    public function pay(){
        $alipay=Account::where('key','alipay')->find();  //支付宝官方
        $wechat=Account::where('key','wechat')->find();  //微信官方
        $epay=Account::where('key','epay')->find();  //易支付
        $codepay=Account::where('key','codepay')->find();  //码支付
        $alipaycode=Account::where('key','alipaycode')->find();  //支付宝当面付
        $this->assign('alipay',$alipay);
        $this->assign('wechat',$wechat);
        $this->assign('epay',$epay);
        $this->assign('codepay',$codepay);
        $this->assign('alipaycode',$alipaycode);
        return $this->fetch();
    }

    public function sendtestemail(){
        $user=[
            'smtp'=>request()->post('smtp'),
            'email'=>request()->post('email'),
            'pwd'=>request()->post('pwd'),
            'username'=>request()->post('usr'),
        ];
        $subject='格子吧自助售卡系统测试邮件';
        $email=request()->post('send');
        $tmp='如果您收到此邮件，证明您的邮件配置正确';
        list($result,$info)=Email::SendEmail($user,$subject,$email,$tmp);
        if($result==true){
            return $this->json(0,'发送成功');
        } else{
            return $this->json(-4,$info);
        }
    }

    public function payconfig(){
        $type=request()->get('type');
        $this->assign('url',request()->domain());
        switch($type){
            case 'alipay':
                $this->assign('alipay',Account::where('key','alipay')->find());
                break;
            case 'alipaycode':
                $this->assign('alipaycode',Account::where('key','alipaycode')->find());
                break;
            case 'wechat':$this->assign('wechat',Account::where('key','wechat')->find());break;
            case 'codepay':$this->assign('codepay',Account::where('key','codepay')->find());break;
            case 'epay':$this->assign('epay',Account::where('key','epay')->find());break;
        }
        return $this->fetch('config/'.$type.'conf');
    }

    public function paystateconfig(){
        $field=request()->post('field');
        $key=request()->post('key');
        $state=request()->post('state');
        Account::where('key',$key)->update([
            $field=>$state
        ]);
        return $this->json();
    }

    public function payconfighandle(){
        $type=request()->get('type');
        switch($type){
            case 'alipay':return $this->alipayConfig();break;
            case 'alipaycode':return $this->alipayCodeConfig();break;
            case 'wechat':return $this->wechatConfig();break;
            case 'codepay':return $this->codepayConfig();break;
            case 'epay':return $this->epayConfig();break;
        }
    }

    private function alipayCodeConfig(){
        $config=0;
        if(request()->post('appid','')!=''){
            $config=1;
        }
        $data=[
            'data'=>serialize([
                'appid'=>request()->post('appid',''),
                'pubkey'=>request()->post('pubkey',''),
                'private'=>request()->post('private','')
            ]),
            'pc'=>request()->post('pc',0),
            'wap'=>request()->post('wap',0),
            'config'=>$config,
            'update_time'=>time()
        ];
        $info=Account::where('key','alipaycode')->find();
        if(!$info){
            $data['key']='alipaycode';
            $info=new Account($data);
            $info->save();
        } else{
            Account::where('key','alipaycode')->update($data);
        }
        return $this->json(0,'配置成功');
    }

    public function epayConfig(){
        $merid=request()->post('merid');
        $key=request()->post('key');
        $alipay=request()->post('alipay',0);
        $wechat=request()->post('wechat',0);
        $info=Account::where('key','epay')->find();
        $data=[
            'data'=>serialize([
                'merid'=>$merid,
                'key'=>$key
            ]),
            'alipay'=>$alipay,
            'wechat'=>$wechat,
            'update_time'=>time()
        ];
        if($merid!=null&&strlen($merid)>5){
            $data['config']=1;
        }
        if(!$info){
            $data['key']='epay';
            $info=new Account($data);
            $info->save();
        } else{
            Account::where('key','epay')->update($data);
        }
        return $this->json(0,'配置成功');
    }

    private function codepayConfig(){
        $merid=request()->post('merid','');
        $key=request()->post('key');
        $alipay=request()->post('alipay',0);
        $wechat=request()->post('wechat',0);
        $info=Account::where('key','codepay')->find();
        $data=[
            'data'=>serialize([
                'merid'=>$merid,
                'key'=>$key
            ]),
            'alipay'=>$alipay,
            'wechat'=>$wechat,
            'update_time'=>time()
        ];
        if($merid!=null&&strlen($merid)>5){
            $data['config']=1;
        }
        if(!$info){
            $data['key']='codepay';
            $info=new Account($data);
            $info->save();
        } else{
            Account::where('key','codepay')->update($data);
        }
        return $this->json(0,'配置成功');
    }

    private function alipayConfig(){
        $config=0;
        if(request()->post('appid','')!=''){
            $config=1;
        }
        $data=[
            'data'=>serialize([
                'appid'=>request()->post('appid',''),
                'pubkey'=>request()->post('pubkey',''),
                'private'=>request()->post('private','')
            ]),
            'pc'=>request()->post('pc',0),
            'wap'=>request()->post('wap',0),
            'config'=>$config,
            'update_time'=>time()
        ];
        $info=Account::where('key','alipay')->find();
        if(!$info){
            $data['key']='alipay';
            $info=new Account($data);
            $info->save();
        } else{
            Account::where('key','alipay')->update($data);
        }
        return $this->json(0,'配置成功');
    }

    private function wechatConfig(){
        $config=0;
        if(request()->post('appid','')!=''){
            $config=1;
        }
        $data=[
            'data'=>serialize([
                'appid'=>request()->post('appid',''),
                'key'=>request()->post('key',''),
                'merchantid'=>request()->post('merchantid','')
            ]),
            'pc'=>request()->post('pc',0),
            'wap'=>request()->post('wap',0),
            'config'=>$config,
            'update_time'=>time()
        ];
        $info=Account::where('key','wechat')->find();
        if(!$info){
            $data['key']='wechat';
            $info=new Account($data);
            $info->save();
        } else{
            Account::where('key','wechat')->update($data);
        }
        // Account::where('key','wechat')->update($data);
        return $this->json(0,'配置成功');
    }
}