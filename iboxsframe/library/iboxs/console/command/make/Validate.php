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

class Validate extends Make
{
    protected $type = "Validate";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:validate')
            ->setDescription('Create a validate class');
    }

    protected function getStub()
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR;

        return $stubPath . 'validate.stub';
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, $module) . '\validate';
    }

}
