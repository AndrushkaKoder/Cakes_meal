<?php

namespace core\exceptions;

use core\system\Logger;

class RouteException extends \Exception
{

    protected $messages;

    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message, $code);

        $this->messages = include __DIR__ . '/messages/messages.php';

        $error = $this->getMessage() ?: $this->messages[$this->getCode()];

        $error .= "\r\n" . 'file ' . $this->getFile() . "\r\n" . 'In line ' . $this->getLine() . "\r\n";

        if($this->messages[$this->getCode()]){

            $this->message = $this->messages[$this->getCode()];

        }

        Logger::instance()->writeLog($error);

    }

    public function showMessage(){

        header("HTTP/1.1 404 Not Found", true, 404);
        header ('Status: 404 Not Found');

        return new \core\system\ErrorController($this->message);

    }

}