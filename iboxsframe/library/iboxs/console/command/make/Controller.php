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
use iboxs\console\input\Option;
use iboxs\facade\Config;

class Controller extends Make
{
    protected $type = "Controller";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:controller')
            ->addOption('api', null, Option::VALUE_NONE, 'Generate an api controller class.')
            ->addOption('plain', null, Option::VALUE_NONE, 'Generate an empty controller class.')
            ->setDescription('Create a new resource controller class');
    }

    protected function getStub()
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR;

        if ($this->input->getOption('api')) {
            return $stubPath . 'controller.api.stub';
        }

        if ($this->input->getOption('plain')) {
            return $stubPath . 'controller.plain.stub';
        }

        return $stubPath . 'controller.stub';
    }

    protected function getClassName($name)
    {
        return parent::getClassName($name) . (Config::get('controller_suffix') ? ucfirst(Config::get('url_controller_layer')) : '');
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, $module) . '\controller';
    }

}
