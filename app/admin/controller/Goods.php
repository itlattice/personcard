<?php
namespace app\admin\controller;

use app\admin\BaseController;
use app\common\Config as CommonConfig;
use app\common\ShortLink;
use app\model\Card;
use app\model\Goods as ModelGoods;
use app\model\GoodsPrice;
use app\model\GoodsSpecs;
use app\model\GoodsType;
use app\model\UserGroup;

class Goods extends BaseController{
    /**
     * 商品分类
     */
    public function type(){
        return $this->fetch();
    }

    public function index(){
        return $this->fetch();
    }

    public function typelist(){
        $basic=GoodsType::order('gtid','ASC');
        $map=(function($item){
            $item['number']=ModelGoods::where('gtid',$item['gtid'])->count();
            return $item;
        });
        return $this->layJson($basic,$map);
    }

    public function deltype(){
        $id=request()->param('id',0);
        $type=GoodsType::get($id);
        if($type){
            $type->delete();
        }
        return $this->json(0,'删除成功');
    }

    public function shorturl(){
        $id=request()->param('id',0);
        $goods=ModelGoods::get($id);
        $short=$goods['short'];
        $url=request()->domain().'/goods/'.$id.'.html';
        $this->assign('long',$url);
        if($short==null){
            $short=ShortLink::GetShortLink($url);
            ModelGoods::where('gid',$id)->update([
                'short'=>$short
            ]);
        }
        $this->assign('url',$short);
        $this->assign('info',$goods);
        return $this->fetch();
    }

    public function addtype(){
        $id=request()->param('id',0);
        if(request()->isGet()){
            $this->assign('id',$id);
            if($id>0){
                $this->assign('info',GoodsType::get($id));
            }
            return $this->fetch();
        }
        $data=[
            'name'=>request()->post('name',''),
            'sort'=>request()->post('sort'),
            'icon'=>request()->post('icon',''),
            'goods_sort'=>request()->post('goods_sort',''),
            'dec'=>request()->post('dec','')
        ];

        if($id>0){
            GoodsType::where('gtid',$id)->update($data);
        } else{
            $info=new GoodsType($data);
            $info->save();
        }
        return $this->json();
    }

    public function list(){
        $is_sale=request()->post('is_sale',-1);
        $type=request()->post('type',-1);
        $is_head=request()->post('is_head',-1);
        
        $keyword=request()->post('keyword','');
        $basic=ModelGoods::with('goodstype')->order('gid','DESC');
        if($is_sale!=null && $is_sale>-1){
            $basic=$basic->where('is_sale',$is_sale);
        }
        if($type!=null&& $type>-1){
            $basic=$basic->where('type',$type);
        }
        if($is_head!=null&& $is_head>-1){
            $basic=$basic->where('is_head',$is_head);
        }
        if($keyword!=''){
            $basic=$basic->where('name','like',"%{$keyword}%");
        }
        // dump($basic->buildSql());
        return $this->layJson($basic);
    }

    public function addgoods(){
        $type=GoodsType::where('gtid',">",0)->select();
        $this->assign('type',$type);
        $this->assign('group',UserGroup::select());
        return $this->fetch();
    }

    public function addgoodshandle(){
        $price=request()->post('price');
        $data=request()->post('data');
        $goods=[
            'gtid'=>$data['gtid'],
            'name'=>$data['name'],
            'dec'=>$data['dec'],
            'is_pwd'=>$data['is_pwd'],
            'is_login'=>$data['is_login'],
            'top'=>-1,
            'sale'=>$data['sale'],
            'img'=>$data['img'],
            'type'=>$data['type'],
            'num'=>1,
            'is_head'=>$data['is_head'],
            'is_sale'=>$data['is_sale']??1,
            'stock'=>$data['stock'],
            'price'=>$data['price'],
            'other'=>$data['other']??0,
            'sort'=>$data['sort'],
            'pwd'=>$data['pwd'],
            'details'=>$data['detail'],
            'window'=>$data['window']??''
        ];
        $goodsInfo=new ModelGoods($goods);
        $goodsInfo->save();
        $gid=$goodsInfo->gid;  //商品ID
        /************************************/
        $group=UserGroup::select();
        foreach($price as $k=>$v){
            $specs=new GoodsSpecs([
                'gid'=>$gid,
                'name'=>$v['name']
            ]);
            $specs->save();
            $gsid=$specs->gsid;
            foreach($group as $uk=>$uv){
                $sale_price=$v['price'.$uv['gid']];
                $prices=$v['from'];
                $cost=$v['unit'];
                $priceInfo=new GoodsPrice([
                    'gid'=>$gid,
                    'gsid'=>$gsid,
                    'ugid'=>$uv['gid'],
                    'price'=>$prices,
                    'sale_price'=>$sale_price,
                    'cost'=>$cost
                ]);
                $priceInfo->save();
            }
        }
        return $this->json(0,'添加成功');
    }

    public function editgoodshandle(){
        $id=request()->param('id');
        $goods=ModelGoods::get($id);
        if(!$goods){
            return $this->json(-7,'异常操作');
        }
        $price=request()->post('price');
        $data=request()->post('data');
        $goods=[
            'gtid'=>$data['gtid'],
            'name'=>$data['name'],
            'dec'=>$data['dec'],
            'is_pwd'=>$data['is_pwd'],
            'is_login'=>$data['is_login'],
            'top'=>-1,
            'sale'=>$data['sale'],
            'img'=>$data['img'],
            'type'=>$data['type'],
            'num'=>1,
            'is_head'=>$data['is_head'],
            'is_sale'=>$data['is_sale']??1,
            'stock'=>$data['stock'],
            'price'=>$data['price'],
            'other'=>$data['other']??0,
            'sort'=>$data['sort'],
            'pwd'=>$data['pwd'],
            'details'=>$data['detail'],
            'window'=>$data['window']??''
        ];
        ModelGoods::where('gid',$id)->update($goods);
        $group=UserGroup::select();
        foreach($price as $k=>$v){
            $specsId=$v['id']??0;
            if($specsId==0){  //新增规格
                $specs=new GoodsSpecs([
                    'gid'=>$id,
                    'name'=>$v['name'],
                    'update_time'=>time()
                ]);
                $specs->save();
                $gsid=$specs->gsid;
                foreach($group as $uk=>$uv){
                    $sale_price=$v['price'.$uv['gid']];
                    $prices=$v['from'];
                    $cost=$v['unit'];
                    $priceInfo=new GoodsPrice([
                        'gid'=>$id,
                        'gsid'=>$gsid,
                        'ugid'=>$uv['gid'],
                        'price'=>$prices,
                        'sale_price'=>$sale_price,
                        'cost'=>$cost
                    ]);
                    $priceInfo->save();
                }
            } else{  //修改规格
                GoodsSpecs::where('gsid',$specsId)->update([
                    'name'=>$v['name'],
                    'update_time'=>time()
                ]);
                foreach($group as $uk=>$uv){
                    $sale_price=$v['price'.$uv['gid']];
                    $prices=$v['from'];
                    $cost=$v['unit'];
                    $pricedata=GoodsPrice::where(array(
                        'gid'=>$id,
                        'gsid'=>$specsId,
                        'ugid'=>$uv['gid'],
                    ))->find();
                    if($pricedata){
                        GoodsPrice::where(array(
                            'gid'=>$id,
                            'gsid'=>$specsId,
                            'ugid'=>$uv['gid'],
                        ))->update([
                            'price'=>$prices,
                            'sale_price'=>$sale_price,
                            'cost'=>$cost
                        ]);
                    } else{
                        $priceInfo=new GoodsPrice([
                            'gid'=>$id,
                            'gsid'=>$specsId,
                            'ugid'=>$uv['gid'],
                            'price'=>$prices,
                            'sale_price'=>$sale_price,
                            'cost'=>$cost
                        ]);
                        $priceInfo->save();
                    }
                }
            }
        }
        GoodsSpecs::where('gid',$id)->where('update_time','<',time()-10)->delete();
        return $this->json();
    }

    public function delgoods(){
        $id=request()->post('id');
        $goods=ModelGoods::get($id);
        if($goods){
            $goods->delete();
        }
        return $this->json(0,'删除成功');
    }

    public function updatedata(){
        $val=request()->post('val');
        $field=request()->post('field');
        $id=request()->post('id');
        if($field=='is_sale'){
            $is_sale=ModelGoods::where('gid',$id)->value('is_sale');
            if($is_sale>0){
                $val=0;
            } else{
                $val=1;
            }
        }
        ModelGoods::where('gid',$id)->update([
            $field=>$val
        ]);
        return $this->json();
    }

    public function addcard(){
        $id=request()->param('id',0);
        $goods=ModelGoods::get($id);
        if($goods['is_head']==1||$goods['type']!=0){
            return '商品不允许加卡';
        }
        if(request()->isGet()){
            $this->assign('name',$goods['name']);
            $specs=GoodsSpecs::where('gid',$id)->order('sort')->field(['name','gsid'])->select();
            $this->assign('specs',$specs);
            return $this->fetch();
        }
        $cards=request()->post('card');
        $again=request()->post('again',1);
        $gsid=request()->post('gsid');
        $data=[];
        $number=0;
        foreach($cards as $k=>$card){
            $arr=explode('|',$card);
            $c=$arr[0];
            if(count($arr)>1){
                $pwd=$arr[1];
            } else{
                $pwd=null;
            }
            if($again>0){
                $count=Card::where(array('gpid'=>$gsid,'gid'=>$id,'card'=>$c,'pwd'=>$pwd))->count();
                if($count>0){ //发生重复
                    continue;
                }
            }
            $data[]=array(
                'gid'=>$id,
                'gpid'=>$gsid,
                'card'=>$c,
                'pwd'=>$pwd
            );
            $number++;
        }
        Card::insertAll($data);
        return $this->json(0,"添加成功,共成功添加{$number}张卡密",[],['count'=>$number]);
    }

    public function card(){
        $id=request()->param('id',0);
        $goods=ModelGoods::get($id);
        if($goods['is_head']==1||$goods['type']!=0){
            return '商品不允许加卡';
        }
        if(request()->isGet()){
            $this->assign('name',$goods['name']);
            $specs=GoodsSpecs::where('gid',$id)->order('sort')->field(['name','gsid'])->select();
            $this->assign('specs',$specs);
            return $this->fetch();
        }
        $state=request()->post('state',-1);
        $gpid=request()->post('gpid',-1);
        if($gpid==-1){
            $gpid=GoodsSpecs::where('gid',$id)->order('sort')->value('gsid');
        }
        $card=Card::where(array('gid'=>$id,'gpid'=>$gpid))->order('cid');
        if($state>-1){
            $card=$card->where('state',$state);
        }
        $keyword=request()->post('keyword','');
        if($keyword!=''){
            $card=$card->where('card|pwd','like',"%{$keyword}%");
        }
        return $this->layJson($card);
    }

    public function editgoods(){
        $id=request()->param('id',0);
        $info=ModelGoods::where('gid',$id)->find();
        $specs=GoodsSpecs::where('gid',$id)->with(['price'])->order('sort')->select();
        $this->assign('info',$info);
        $this->assign('specs',$specs);
        $this->assign('type',GoodsType::select());
        $group=UserGroup::select();
        $this->assign('group',$group);
        $price=[];
        foreach($specs as $s){
            $from=0;
            $unit=0;
            foreach($group as $g){
                $priceInfo=GoodsPrice::GetGoodsPrice($s['gid'],$s['gsid'],$g['gid']);
                $from=$priceInfo['price'];
                $unit=$priceInfo['cost'];
                $item['price'.$g['gid']]=$priceInfo['sale_price'];
            }
            $item['name']=$s['name'];
            $item['from']=$from;
            $item['unit']=$unit;
            $item['id']=$s['gsid'];
            $price[]=$item;
        }
        $this->assign('price',json_encode($price,JSON_UNESCAPED_UNICODE));
        return $this->fetch();
    }

    public function cardhandle(){
        $id=request()->post('id',0);
        $state=request()->post('state');
        $card=Card::get($id);
        if($state==-1){
            $card->delete();
            return $this->json(0,'删除成功');
        } else{
            $card->state=$state;
            $card->sale_time=time();
            $card->save();
        }
        return $this->json();
    }

    public function form(){
        if(request()->isGet()){
            return $this->fetch();
        }
    }

    public function updateurl(){
        $id=request()->param('id',0);
        $goods=ModelGoods::get($id);
        if($goods['is_head']==1||$goods['type']!=1){
            return '商品不允许修改链接';
        }
        if(request()->isGet()){
            $this->assign('name',$goods['name']);
            $specs=GoodsSpecs::where('gid',$id)->order('sort')->field(['name','gsid'])->select();
            $this->assign('specs',$specs);
            list($result,$url)=Card::getUrl($id,$specs[0]['gsid']);
            $this->assign('url',$url);
            return $this->fetch();
        }
        $gpid=request()->post('specs');
        $card=Card::where('gpid',$gpid)->delete();
        $card=new Card([
            'gid'=>$id,
            'gpid'=>$gpid,
            'card'=>request()->post('url')
        ]);
        $card->save();
        return $this->json();
    }
}