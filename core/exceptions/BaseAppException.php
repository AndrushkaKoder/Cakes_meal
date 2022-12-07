<?php

namespace core\exceptions;

use core\system\Logger;

abstract class BaseAppException extends \Exception
{

    protected $messages;

    protected function writeLog($logFileName = 'log.txt'){

        $this->messages = include __DIR__ . '/messages/messages.php';

        $error = $this->getMessage() ?: $this->messages[$this->getCode()];

        $error .= "\r\n" . 'file ' . $this->getFile() . "\r\n" . 'In line ' . $this->getLine() . "\r\n";

        foreach (debug_backtrace() as $key => $item){

            $key && $error .= ($item['file'] ?? ($item['class'] ?? '')) . '::' . $item['function'] .
                (isset($item['line']) ? ' - ' . $item['line'] : '') . "\r\n";

        }

        Logger::instance()->writeLog($error, $logFileName);

    }


}