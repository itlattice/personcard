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
use iboxs\console\input\Argument;
use iboxs\facade\App;

class Command extends Make
{
    protected $type = "Command";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:command')
            ->addArgument('commandName', Argument::OPTIONAL, "The name of the command")
            ->setDescription('Create a new command class');
    }

    protected function buildClass($name)
    {
        $commandName = $this->input->getArgument('commandName') ?: strtolower(basename($name));
        $namespace   = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

        $class = str_replace($namespace . '\\', '', $name);
        $stub  = file_get_contents($this->getStub());

        return str_replace(['{%commandName%}', '{%className%}', '{%namespace%}', '{%app_namespace%}'], [
            $commandName,
            $class,
            $namespace,
            App::getNamespace(),
        ], $stub);
    }

    protected function getStub()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'command.stub';
    }

    protected function getNamespace($appNamespace, $module)
    {
        return $appNamespace . '\\command';
    }

}
