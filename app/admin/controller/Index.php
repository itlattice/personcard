<?php
namespace app\admin\controller;

use app\admin\BaseController;
use app\common\Cache as CommonCache;
use app\common\Config;
use app\model\Goods;
use app\model\Order;
use app\model\User;
use iboxs\facade\Cache;

class Index extends BaseController{
    public function index(){
        return $this->fetch();
    }

    public function main(){
        $this->assign('userinfo',$this->user);
        $this->assign('goods',Goods::count());
        $this->assign('user',User::count());
        $count=Order::where('state','>',0)->count();
        $this->assign('order',$count);
        $amount=sprintf('%.2f',Order::where('state','>',0)->sum('info_price'));
        $this->assign('amount',$amount);
        if($count==0){
            $count=1;
        }
        $this->assign('useramount',sprintf('%.2f',round($amount/$count,2)));
        $this->assign('todayOrder',Order::where('state','>',0)->where('add_time','>',strtotime(date('Y-m-d')))->count());
        return $this->fetch();
    }

    public function clearcache(){
        Cache::clear();
        deldir(LOG_PATH.'temp/');
        deldir(LOG_PATH.'log/');
        return $this->json(0,'清除成功');
    }

    public function newauth(){
        $cache=CommonCache::get('newauthtime');
        if($cache!=null){
            if(abs(time()-$cache)<300){
                return $this->json(-4,'5分钟只可以刷新一次，请'.(300-time()+$cache).'秒后再试');
            }
        }
        if(file_exists(LOG_PATH.'license.key')){
            @unlink(LOG_PATH.'license.key');
        }
        CommonCache::set('newauthtime',time(),300);
        return $this->json(0,'刷新成功');
    }
}