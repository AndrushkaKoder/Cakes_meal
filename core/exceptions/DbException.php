<?php

namespace webQExceptions;

use webQSystem\Logger;

class DbException extends BaseAppException
{

    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message, $code);

        $this->writeLog('db_log.txt');

    }

    public function showMessage(){

        \WqH::set404();

        return new \webQSystem\ErrorController($this->message);

    }

}