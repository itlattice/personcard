<?php
// +----------------------------------------------------------------------
// | iboxsframe [ WE CAN DO IT JUST iboxs ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://iboxsframe.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace iboxs\route\dispatch;

use iboxs\route\Dispatch;

class Callback extends Dispatch
{
    public function exec()
    {
        // 执行回调方法
        $vars = array_merge($this->request->param(), $this->param);

        return $this->app->invoke($this->dispatch, $vars);
    }

}
