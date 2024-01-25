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

use iboxs\facade\Config;
use iboxs\facade\Route;

require __DIR__.'/CaptchaController.php';
require __DIR__.'/Captcha.php';

Route::get('captcha/[:id]', "\\iboxs\\captcha\\CaptchaController@index");

Validate::extend('captcha', function ($value, $id = '') {
    return captcha_check($value, $id);
});

Validate::setTypeMsg('captcha', ':attribute错误!');

/**
 * @param string $id
 * @param array  $config
 * @return \iboxs\Response
 */
function captcha($id = '', $config = [])
{
    $captcha = new \iboxs\captcha\Captcha($config);
    return $captcha->entry($id);
}

/**
 * @param $id
 * @return string
 */
function captcha_src($id = '')
{
    return Url::build('/captcha' . ($id ? "/{$id}" : ''));
}

/**
 * @param $id
 * @return mixed
 */
function captcha_img($id = '')
{
    return '<img src="' . captcha_src($id) . '" alt="captcha" />';
}

/**
 * @param        $value
 * @param string $id
 * @param array  $config
 * @return bool
 */
function captcha_check($value, $id = '')
{
    $captcha = new \iboxs\captcha\Captcha((array) Config::pull('captcha'));
    return $captcha->check($value, $id);
}
