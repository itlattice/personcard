<?php
namespace app\user;

use app\Base;
use app\common\Cookie;

class BaseLoginController extends Base
{
    public function __construct()
    {
        parent::__construct();
        $list=Cookie::CheckCookie();
        if($list!==false){
            header("Location: /");
            exit();
            return redirect('/')->send();
        }
    }
}