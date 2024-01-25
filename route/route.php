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

use iboxs\facade\Route;

Route::get('admin/login','admin/Login/login');
Route::get('captcha','index/Common/captcha');
Route::get('goods/:id','index/Goods/index');
Route::get('order/:ordernum','index/Order/View');
Route::get('order','index/Order/Index');
Route::any('order/pay','index/Order/Paytype');
Route::any('paynotice/:key','index/Pay/Notice');
Route::any('payreturn/:key','index/Pay/Results');
Route::any('paystauts','index/Pay/PayState');
Route::get('orderview','index/Order/orderview');
Route::get('login','user/Login/index');
Route::get('qqlogin','user/Login/QQLogin');
Route::any('oauthqq','user/Login/oauthqq');
Route::get('loginout','user/Index/Loginout');
Route::get('reg','user/Login/reg');
Route::get('orderresult/:order','index/Order/orderResult');
Route::get('ordercard','index/Order/cardView');
Route::get('openwindow','index/Common/openWindow');