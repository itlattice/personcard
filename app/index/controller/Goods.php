<?php
namespace app\index\controller;

use app\common\Goods as CommonGoods;
use app\index\BaseController;
use app\model\Goods as ModelGoods;

class Goods extends BaseController
{
    public function index($id){
        $goods=ModelGoods::with('specs')->get($id);
        $this->assign('goods',$goods);
        $price=CommonGoods::getGoodsPriceList($this->userinfo['gid']??0,$id);
        $this->assign('price',$price);
        $fromprice=CommonGoods::getGoodsFromPriceList($this->userinfo['gid']??0,$id);;
        $this->assign('fromprice',$fromprice);
        return $this->fetch();
    }

    public function getprice(){
        $gid=request()->post('goodsid');
        $specs=request()->post('specs');
        $num=request()->post('num',1);
        $ugid=$this->userinfo['gid']??0;
        list($form,$unit,$sale,$stock)=CommonGoods::getGoodsPrice($gid,$specs,$ugid,$num);
        return $this->json(0,'获取成功',[
            'form'=>sprintf('%.2f',$form),
            'sale'=>sprintf('%.2f',$sale),
            'stock'=>$stock
        ]);
    }
}