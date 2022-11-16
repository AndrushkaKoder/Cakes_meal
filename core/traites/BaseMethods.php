<?php

namespace core\traites;

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

}