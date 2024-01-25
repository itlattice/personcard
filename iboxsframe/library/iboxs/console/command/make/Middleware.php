<?php
// +----------------------------------------------------------------------
// | iboxsframe [ WE CAN DO IT JUST iboxs ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://iboxsframe.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 刘志淳 <chun@engineer.com>
// +----------------------------------------------------------------------

namespace iboxs\console\command\make;

use iboxs\console\command\Make;

class Middleware extends Make
{
    protected $type = "Middleware";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:middleware')
            ->setDescription('Create a new middleware class');
    }

    protected function getStub()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'middleware.stub';
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, 'http') . '\middleware';
    }
}
