<?php
spl_autoload_register(function ($className){
    $classNameArr = preg_split('/\\\/',$className);
   include $_SERVER['DOCUMENT_ROOT'] . '/test/' . array_pop($classNameArr) . '.php';
});

$t = new \andrew\T;
$a=1;