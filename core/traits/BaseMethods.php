<?php

namespace webQTraits;

use webQModels\UserModel;
use webQSystem\Router;

trait BaseMethods
{

    protected array $messages = [];

    protected function addSessionData(){

        if(\WqH::isPost()){

            foreach ($_POST as $key => $value){

                $_SESSION['res'][$key] = $value;

            }

            \WqH::redirect();

        }

    }

    protected function emptyFields($value, $answer){

        if(empty($value)){

            $_SESSION['res']['answer'] = '<div class="error">' . $this->messages['empty'] . ' ' .$answer . '</div>';

            $this->addSessionData();

        }
    }

    protected function getMessages(){

        if($this->messages){

            return $this->messages;

        }

        if(is_dir(realpath(__DIR__ . '/../') . '/messages')){

            \WqH::scanDir(realpath(__DIR__ . '/../') . '/messages', function ($file){

                $this->messages = array_merge($this->messages, include realpath(__DIR__ . '/../') . '/messages/' . $file);

            });

        }

    }


    protected function setFormValues($key, $property = null, $arr = []){

        if(!$arr){

            $arr = $_SESSION['res'] ?? [];

        }

        if(!empty($arr[$key])){

            return $arr[$key];

        }elseif ($property && !empty($this->$property[$key])){

            return $this->$property[$key];

        }

        return '';

    }

    protected function checkAuth($type = false){

        if(!($this->userData = UserModel::instance()->checkUser(false, $type))){

            if($type &&
                (empty(\Wq::config()->WEB('admin', 'unblocked_access')) ||
                    !in_array($this->getController(), (array)\Wq::config()->WEB('admin', 'unblocked_access')))){

                $type && \WqH::redirect(\Wq::PATH());

            }

        }

        if(property_exists($this, 'userModel'))
            $this->userModel = UserModel::instance();

        if(property_exists($this, 'model') && $this->model)
            $this->model->userData = $this->userData;


    }
}