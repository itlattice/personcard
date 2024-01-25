<?php
namespace app;

use app\common\Cache;
use app\common\Config;
use app\common\Cookie;
use app\model\Config as ModelConfig;
use iboxs\Controller;
use iboxs\facade\Cache as FacadeCache;
use iboxs\facade\Response;

class Base extends Controller{

    public function __construct()
    {
        parent::__construct($this->app);
        // if(request()->isPost()){
        //     $token=request()->post('token');
        //     if($token==null){
        //         return json([
        //             'code'=>-1,
        //             'msg'=>'非法请求'
        //         ])->send();
        //     }
        //     $num=Cache::get('webtoken-'.$token);
        //     if($num==null||$num>30) return json([
        //         'code'=>-1,
        //         'msg'=>'操作超时，请刷新页面'
        //     ])->send();
        //     FacadeCache::inc('webtoken-'.$token);
        // }
    }

    /**
     * 加载模板输出
     * @access protected
     * @param  string $template 模板文件名
     * @param  array  $vars     模板输出变量
     * @param  array  $config   模板参数
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $config = [])
    {
        $this->assign('webConfig',self::getWebConfig());
        $this->assign('sysConfig',self::getSysConfig());
        $this->assign('isLogin',Cookie::CheckCookie()!==false);
        return parent::fetch($template,$vars,$config);
    }

    /**
     * 获取网站配置信息
     */
    public static function getWebConfig(){
        $cache=Cache::get('webconfig');
        if($cache!=null) return $cache;
        $cache=ModelConfig::where('key','webconfig')->value('value');
        $cache=unserialize($cache);
        Cache::set('webconfig',$cache,3600);
        return $cache;
    }

    public static function getSysConfig(){
        $config=Cache::get('sysconfig');
        if($config!=null) return $config;
        $info=ModelConfig::select();
        $config=[];
        foreach($info as $k=>$v){
            $value=$v['value'];
            if(is_serialized($value)){
                $value=unserialize($value);
            }
            $config[$v['key']]=$value;
        }
        Cache::set('sysconfig',$config);
        return $config;
    }

    public function json($code=0,$msg='操作成功',$data=[],$other=[]){
        $result=[
            'code'=>$code,
            'msg'=>$msg
        ];
        if($data!=[]){
            $result['data']=$data;
        }
        foreach($other as $k=>$v){
            $result[$k]=$v;
        }
        return json($result);
    }

    public function layJson($data,$map=null){
        $count=$data->count();
        $limit=request()->post('limit',25);
        $page=request()->post('page',1);
        $list=$data->page($page,$limit)->select();
        if($map!=null){
            $list=$list->map($map);
        }
        return $this->json(0,'获取成功',$list,['count'=>$count]);
    }
}