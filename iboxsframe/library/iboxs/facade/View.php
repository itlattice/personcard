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
 * @see \iboxs\View
 * @mixin \iboxs\View
 * @method \iboxs\View init(mixed $engine = [], array $replace = []) static 初始化
 * @method \iboxs\View share(mixed $name, mixed $value = '') static 模板变量静态赋值
 * @method \iboxs\View assign(mixed $name, mixed $value = '') static 模板变量赋值
 * @method \iboxs\View config(mixed $name, mixed $value = '') static 配置模板引擎
 * @method \iboxs\View exists(mixed $name) static 检查模板是否存在
 * @method \iboxs\View filter(Callable $filter) static 视图内容过滤
 * @method \iboxs\View engine(mixed $engine = []) static 设置当前模板解析的引擎
 * @method string fetch(string $template = '', array $vars = [], array $config = [], bool $renderContent = false) static 解析和获取模板内容
 * @method string display(string $content = '', array $vars = [], array $config = []) static 渲染内容输出
 */
class View extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'view';
    }
}
