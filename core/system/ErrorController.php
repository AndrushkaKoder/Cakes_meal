<?php

namespace webQSystem;

class ErrorController extends Controller
{

    public function __construct($message){

        $trace = debug_backtrace();

        $path = str_replace('\\', '/', $trace[0]['file']);

        $path = preg_split('/\//', $path);

        array_pop($path);

        $fileErrorView = implode('/', $path) . '/errorView/404';

        if(!file_exists($fileErrorView . '.php')){

            exit($message);

        }

        exit($this->render($fileErrorView, ['message' => $message]));

    }

}