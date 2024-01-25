<?php
namespace app\index\controller;

use app\common\Config;
use app\index\BaseController;
use app\model\Goods;
use iboxs\captcha\Captcha;
use iboxs\facade\Cookie;

class Common extends BaseController{
    public function captcha(){
        $captcha = new Captcha();
        return $captcha->entry();  
    }

    public function openWindow(){
        $key=request()->get('key');
        Cookie::set('window'.$key,'open',6*3600);
        if($key=='goodswindows'){
            $id=request()->get('id',0);
            if($id>0){
                $html=Goods::get($id)['window'];
                return $html;
            }
        }
        $html=Config::get($key,'');
        return $html;
    }
}