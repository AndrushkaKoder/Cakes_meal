<?php

namespace core\traites;

use core\models\UserModel;

trait BaseMethods
{

    protected array $messages = [];

    protected function addSessionData(){

        if(\AppH::isPost()){

            foreach ($_POST as $key => $value){

                $_SESSION['res'][$key] = $value;

            }

            \AppH::redirect();

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

            \AppH::scanDir(realpath(__DIR__ . '/../') . '/messages', function ($file){

                $this->messages = array_merge($this->messages, include realpath(__DIR__ . '/../') . '/messages/' . $file);

            });

        }

    }

    protected function writeLog($message, $file = 'log.txt', $event = 'Fault', $rotateLogs = true){

        $dateTime = new \DateTime();

        if($event !== 0) $str = $event . ': ' . $dateTime->format('d-m-Y G:i:s') . ' - ' . $message . "\r\n";
        else $str = $message . "\r\n";

        $dir = $_SERVER['DOCUMENT_ROOT'] . \App::PATH() . 'log';

        if(!is_dir($dir)){

            mkdir($dir, 0777);

        }

        $fileArr = preg_split('/\./', $file, 0, PREG_SPLIT_NO_EMPTY);

        if(!empty($fileArr[count($fileArr) - 2])){

            $fileArr[count($fileArr) - 2] .= '_' . $dateTime->format('Y_m_d');

            $file = implode('.', $fileArr);

        }

        if($rotateLogs){

            $this->rotateLogs($dir);

        }

        file_put_contents($dir . '/' . $file, $str, FILE_APPEND);

    }

    protected function rotateLogs($dir, $day = 30){

        $list = scandir($dir);

        if($list){

            foreach ($list as $file){

                if($file !== '.' && $file !== '..' && !is_dir($dir . '/' . $file) && !is_link($dir . '/' . $file)){

                    if((new \DateTime(date('Y-m-d', filemtime($dir . '/' . $file)))) < (new \DateTime())->modify('-' . $day . ' day')){

                        @unlink($dir . '/' . $file);

                    }

                }

            }

        }

    }

    protected function addSessionData(){
        if(\AppH::isPost()){
            foreach ($_POST as $key => $value){
                $_SESSION['res'][$key] = $value;
            }
            \AppH::redirect();
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

            $type && \AppH::redirect(\App::PATH());

        }

        if(property_exists($this, 'userModel'))
            $this->userModel = UserModel::instance();

        if(property_exists($this, 'model') && $this->model)
            $this->model->userData = $this->userData;


    }
}