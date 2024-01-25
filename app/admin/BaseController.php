<?php
namespace app\admin;

use app\Base;
use app\common\Cookie;
use app\model\Admin;
use app\model\Log;

class BaseController extends Base{

    protected $aid=0;

    protected $user=[];

    public function __construct(){
        parent::__construct($this->app);
        $list=Cookie::CheckAdminCookie();
        if($list==false){
            redirect('/admin/login.html')->send();
            exit();
        }
        list($aid,$info)=$list;
        $this->aid=$aid;
        $this->user=$info;
        Log::InsertLog();
    }

    protected function fetch($template = '', $vars = [], $config = []){
        if($this->aid>0){
            $this->assign('adminInfo',Admin::find($this->aid));
        }
        return parent::fetch($template,$vars,$config);
    }
}