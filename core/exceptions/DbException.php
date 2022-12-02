<?php

namespace core\exceptions;

use core\system\Logger;

class DbException extends \Exception
{

    protected $messages;

    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message, $code);

        $this->messages = include __DIR__ . '/messages/messages.php';

        $error = $this->getMessage() ? $this->getMessage() : $this->messages[$this->getCode()];

        $error .= "\r\n" . 'file ' . $this->getFile() . "\r\n" . 'In line ' . $this->getLine() . "\r\n";

        //if($this->messages[$this->getCode()]) $this->message = $this->messages[$this->getCode()];

        Logger::instance()->writeLog($error, 'db_log.txt');

    }

    public function showMessage(){

        \AppH::set404();

        return new \core\system\ErrorController($this->message);

    }

}