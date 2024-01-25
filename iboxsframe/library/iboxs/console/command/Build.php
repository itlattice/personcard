<?php
// +----------------------------------------------------------------------
// | iboxsframe [ WE CAN DO IT JUST iboxs ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2015 http://iboxsframe.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace iboxs\console\command;

use iboxs\console\Command;
use iboxs\console\Input;
use iboxs\console\input\Option;
use iboxs\console\Output;
use iboxs\facade\App;
use iboxs\facade\Build as AppBuild;

class Build extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('build')
            ->setDefinition([
                new Option('config', null, Option::VALUE_OPTIONAL, "build.php path"),
                new Option('module', null, Option::VALUE_OPTIONAL, "module name"),
            ])
            ->setDescription('Build Application Dirs');
    }

    protected function execute(Input $input, Output $output)
    {
        if ($input->hasOption('module')) {
            AppBuild::module($input->getOption('module'));
            $output->writeln("Successed");
            return;
        }

        if ($input->hasOption('config')) {
            $build = include $input->getOption('config');
        } else {
            $build = include App::getAppPath() . 'build.php';
        }

        if (empty($build)) {
            $output->writeln("Build Config Is Empty");
            return;
        }

        AppBuild::run($build);
        $output->writeln("Successed");

    }
}
