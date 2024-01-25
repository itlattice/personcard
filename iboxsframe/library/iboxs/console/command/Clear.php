<?php
// +----------------------------------------------------------------------
// | iboxsframe [ WE CAN DO IT JUST iboxs IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://iboxsframe.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace iboxs\console\command;

use iboxs\console\Command;
use iboxs\console\Input;
use iboxs\console\input\Option;
use iboxs\console\Output;
use iboxs\facade\App;
use iboxs\facade\Cache;

class Clear extends Command
{
    protected function configure()
    {
        // 指令配置
        $this
            ->setName('clear')
            ->addOption('path', 'd', Option::VALUE_OPTIONAL, 'path to clear', null)
            ->addOption('cache', 'c', Option::VALUE_NONE, 'clear cache file')
            ->addOption('route', 'u', Option::VALUE_NONE, 'clear route cache')
            ->addOption('log', 'l', Option::VALUE_NONE, 'clear log file')
            ->addOption('dir', 'r', Option::VALUE_NONE, 'clear empty dir')
            ->setDescription('Clear runtime file');
    }

    protected function execute(Input $input, Output $output)
    {
        if ($input->getOption('route')) {
            Cache::clear('route_cache');
        } else {
            if ($input->getOption('cache')) {
                $path = App::getRuntimePath() . 'cache';
            } elseif ($input->getOption('log')) {
                $path = App::getRuntimePath() . 'log';
            } else {
                $path = $input->getOption('path') ?: App::getRuntimePath();
            }

            $rmdir = $input->getOption('dir') ? true : false;
            $this->clear(rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR, $rmdir);
        }

        $output->writeln("<info>Clear Successed</info>");
    }

    protected function clear($path, $rmdir)
    {
        $files = is_dir($path) ? scandir($path) : [];

        foreach ($files as $file) {
            if ('.' != $file && '..' != $file && is_dir($path . $file)) {
                array_map('unlink', glob($path . $file . DIRECTORY_SEPARATOR . '*.*'));
                if ($rmdir) {
                    rmdir($path . $file);
                }
            } elseif ('.gitignore' != $file && is_file($path . $file)) {
                unlink($path . $file);
            }
        }
    }
}
