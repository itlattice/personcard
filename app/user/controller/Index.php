<?php
namespace app\user\controller;

use app\user\BaseController;
use iboxs\facade\Session;

class Index extends BaseController
{
    public function Loginout(){
        Session::delete('uid');
        Session::delete('userinfo');
        Session::delete('browserinfo');
        return redirect('/');
    }
}