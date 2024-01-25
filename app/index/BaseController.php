<?php
namespace app\index;

use app\Base;
use app\common\Cookie;

class BaseController extends Base{
    protected $uid=0;

    protected $userinfo=[];

    public function __construct()
    {
        parent::__construct();
        $list=Cookie::CheckCookie();
        if($list!==false){
            list($uid,$info)=$list;
            $this->uid=$uid;
            $this->userinfo=$info;
        }
    }
}