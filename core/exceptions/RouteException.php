<?php

namespace core\exceptions;

use core\system\Logger;

class RouteException extends BaseAppException
{

    public function __construct($message = "", $code = 0)
    {

        if(\AppH::isHtmlRequest()){

            parent::__construct($message, $code);

            $this->writeLog();

            if($this->messages[$this->getCode()]){

                $this->message = $this->messages[$this->getCode()];

            }

        }


    }

    public function showMessage(){

        \AppH::set404();

        return new \core\system\ErrorController($this->message);

    }

}