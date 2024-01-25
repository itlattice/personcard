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
 * @see \iboxs\Response
 * @mixin \iboxs\Response
 * @method \iboxs\response create(mixed $data = '', string $type = '', int $code = 200, array $header = [], array $options = []) static 创建Response对象
 * @method void send() static 发送数据到客户端
 * @method \iboxs\Response options(mixed $options = []) static 输出的参数
 * @method \iboxs\Response data(mixed $data) static 输出数据设置
 * @method \iboxs\Response header(mixed $name, string $value = null) static 设置响应头
 * @method \iboxs\Response content(mixed $content) static 设置页面输出内容
 * @method \iboxs\Response code(int $code) static 发送HTTP状态
 * @method \iboxs\Response lastModified(string $time) static LastModified
 * @method \iboxs\Response expires(string $time) static expires
 * @method \iboxs\Response eTag(string $eTag) static eTag
 * @method \iboxs\Response cacheControl(string $cache) static 页面缓存控制
 * @method \iboxs\Response contentType(string $contentType, string $charset = 'utf-8') static 页面输出类型
 * @method mixed getHeader(string $name) static 获取头部信息
 * @method mixed getData() static 获取原始数据
 * @method mixed getContent() static 获取输出数据
 * @method int getCode() static 获取状态码
 */
class Response extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'response';
    }
}
