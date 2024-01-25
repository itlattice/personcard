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

namespace iboxs\facade;

use iboxs\Facade;

/**
 * @see \iboxs\Url
 * @mixin \iboxs\Url
 * @method string build(string $url = '', mixed $vars = '', mixed $suffix = true, mixed $domain = false) static URL生成 支持路由反射
 * @method void root(string $root) static 指定当前生成URL地址的root
 */
class Url extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'url';
    }
}
