<?php
// +----------------------------------------------------------------------
// | iboxsframe [ WE CAN DO IT JUST iboxs ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://iboxsframe.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace iboxs\captcha;

use iboxs\facade\Config;

class CaptchaController
{
    public function index($id = "")
    {
        $captcha = new Captcha((array) Config::pull('captcha'));
        return $captcha->entry($id);
    }
}
