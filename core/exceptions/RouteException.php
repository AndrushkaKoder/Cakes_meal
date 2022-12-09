<?php

namespace webQExceptions;

use webQSystem\Logger;

class RouteException extends BaseAppException
{

    public function __construct($message = "", $code = 0)
    {

        if(\WqH::isHtmlRequest()){

            parent::__construct($message, $code);

            $this->writeLog();

            if($this->messages[$this->getCode()]){

                $this->message = $this->messages[$this->getCode()];

            }

        }


    }

    public function showMessage(){

        \WqH::set404();

        return new \webQSystem\ErrorController($this->message);

    }

}