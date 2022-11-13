<?php

function print_arr($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

function showErrors($on = true){

    if($on){

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    }else{

        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);

    }

}