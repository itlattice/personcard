<?php
// +----------------------------------------------------------------------
// | iboxsframe [ WE CAN DO IT JUST iboxs IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://iboxsframe.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace iboxs\exception;

class RouteNotFoundException extends HttpException
{

    public function __construct()
    {
        parent::__construct(404, 'Route Not Found');
    }

}
