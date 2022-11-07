<?php

namespace web\user\controllers;

class LoginController extends BaseUser
{

    protected function actionInput(){

        if(!empty($_POST)){
            foreach ($_POST as $key => $item){
                $_POST[$key] = \AppH::clearStr($item);
            }

            if($_POST['password'] === $_POST['password_repeat']){

                $_POST['password'] = md5($_POST['password']);

                $this->model->add('users');

                header('location:'.$this->alias(''));

            }
            else{
                $message = 'Пароли не совпадают';
            }
        }

            $a=1;

        return compact('message');
    }

}