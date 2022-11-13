<?php

$controlFile = $_SERVER['argv'][1] ?? '';

$executableFile = $_SERVER['argv'][2] ?? '';

if(empty($_SERVER['DOCUMENT_ROOT'])){

    $_SERVER['DOCUMENT_ROOT'] = $_SERVER['argv'][3] ?? realpath(__DIR__ . '/../../');

}

if($controlFile){

    $f = fopen($controlFile, 'w');

    $lock = flock($f, LOCK_EX | LOCK_NB);

}

if($executableFile){

    ignore_user_abort(true);

    if(function_exists('fastcgi_finish_request')){

        fastcgi_finish_request();

    }

    include($executableFile);

}

