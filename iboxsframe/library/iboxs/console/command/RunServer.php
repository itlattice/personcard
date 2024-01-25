<?php
// +----------------------------------------------------------------------
// | iboxsframe [ WE CAN DO IT JUST iboxs ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2015 http://iboxsframe.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Slince <taosikai@yeah.net>
// +----------------------------------------------------------------------
namespace iboxs\console\command;

use iboxs\console\Command;
use iboxs\console\Input;
use iboxs\console\input\Option;
use iboxs\console\Output;
use iboxs\facade\App;

class RunServer extends Command
{
    public function configure()
    {
        $this->setName('run')
            ->addOption('host', 'H', Option::VALUE_OPTIONAL,
                'The host to server the application on', '127.0.0.1')
            ->addOption('port', 'p', Option::VALUE_OPTIONAL,
                'The port to server the application on', 8000)
            ->addOption('root', 'r', Option::VALUE_OPTIONAL,
                'The document root of the application', App::getRootPath() . 'public')
            ->setDescription('PHP Built-in Server for iboxsframe');
    }

    public function execute(Input $input, Output $output)
    {
        $host = $input->getOption('host');
        $port = $input->getOption('port');
        $root = $input->getOption('root');

        $command = sprintf(
            'php -S %s:%d -t %s %s',
            $host,
            $port,
            escapeshellarg($root),
            escapeshellarg($root . DIRECTORY_SEPARATOR . 'router.php')
        );

        $output->writeln(sprintf('iboxsframe Development server is started On <http://%s:%s/>', $host, $port));
        $output->writeln(sprintf('You can exit with <info>`CTRL-C`</info>'));
        $output->writeln(sprintf('Document root is: %s', $root));
        passthru($command);
    }

}
