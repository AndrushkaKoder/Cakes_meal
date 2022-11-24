<?php

namespace core\system;

class ErrorController extends Controller
{

    public function __construct($message){

        $trace = debug_backtrace();

        $path = str_replace('\\', '/', $trace[0]['file']);

        $path = preg_split('/\//', $path, 0, PREG_SPLIT_NO_EMPTY);

        array_pop($path);

        $fileErrorView = implode('/', $path) . '/errorView/404';

        if(!file_exists($fileErrorView . '.php')){

            exit($message);

        }

        exit($this->render($fileErrorView, ['message' => $message]));

    }

}