<?php

namespace core\exceptions;

use core\system\Logger;

class DbException extends BaseAppException
{

    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message, $code);

        $this->writeLog('db_log.txt');

    }

    public function showMessage(){

        \AppH::set404();

        return new \core\system\ErrorController($this->message);

    }

}