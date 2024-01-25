<?php
// [ 应用入口文件 ]
namespace iboxs;
define('APP_PATH',__DIR__.'/../app/');
define('PUBLIC_PATH',__DIR__.'/../public/');
define('CONFIG_PATH',__DIR__.'/../config/');
define('LOG_PATH',__DIR__.'/../runtime/');
define('ROOT_PATH',__DIR__.'/../');
// 加载基础文件
require __DIR__ . '/../iboxsframe/base.php';

$lock=APP_PATH.'install/install.lock';
if(!file_exists($lock)){
    header("Location: /install.php");
    exit();
}

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();
