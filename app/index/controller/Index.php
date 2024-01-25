<?php
namespace app\index\controller;

use app\Base;
use app\index\BaseController;
use app\model\GoodsType;

class Index extends BaseController
{
    public function index()
    {
        $this->assign('notice',Base::getSysConfig()['notice']);
        $this->assign('type',GoodsType::order('sort')->select());
        return $this->fetch();
    }
}
