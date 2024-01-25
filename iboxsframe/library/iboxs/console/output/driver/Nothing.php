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

namespace iboxs\console\output\driver;

use iboxs\console\Output;

class Nothing
{

    public function __construct(Output $output)
    {
        // do nothing
    }

    public function write($messages, $newline = false, $options = Output::OUTPUT_NORMAL)
    {
        // do nothing
    }

    public function renderException(\Exception $e)
    {
        // do nothing
    }
}
