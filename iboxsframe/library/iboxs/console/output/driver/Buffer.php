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

namespace iboxs\console\output\driver;

use iboxs\console\Output;

class Buffer
{
    /**
     * @var string
     */
    private $buffer = '';

    public function __construct(Output $output)
    {
        // do nothing
    }

    public function fetch()
    {
        $content      = $this->buffer;
        $this->buffer = '';
        return $content;
    }

    public function write($messages, $newline = false, $options = Output::OUTPUT_NORMAL)
    {
        $messages = (array) $messages;

        foreach ($messages as $message) {
            $this->buffer .= $message;
        }
        if ($newline) {
            $this->buffer .= "\n";
        }
    }

    public function renderException(\Exception $e)
    {
        // do nothing
    }

}
