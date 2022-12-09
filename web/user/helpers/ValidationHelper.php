<?php

namespace webQApplication\helpers;

use webQSystem\Logger;

trait ValidationHelper
{

    protected function emptyField($value, $answer){

        $value = \WqH::clearStr($value);

        if(empty($value)){

            $this->sendError('Не заполнено поле', $answer);

        }

        return $value;

    }

    protected function numericField($value, $answer, $count = null){

        $value = preg_replace('/\D/', '', $value);

        if(!$value){

            $this->sendError('Некорректное поле', $answer);

        }

        if($count){

            if(strlen($value) !== $count){

                $this->sendError('Длина поля ' . $answer . ' должна содержать ',
                                        $count . ' ' . \WqH::wordsForCounter($count, ['Символов', 'Символ', 'Символа']));

            }

        }

        return $value;

    }

    protected function phoneField($value, $answer = null){

        $value = preg_replace('/\D/', '', $value);

        if(strlen($value) === 11){

            $value = preg_replace('/^8/', '7', $value);

        }

        return $value;

    }

    protected function emailField($value, $answer){

        $value = \WqH::clearStr($value);

        if(!preg_match('/^[\w\-\.]+@[\w\-]+\.[\w\-]+/i', $value)){

            $this->sendError('Не корректный формат для email в поле', $answer);

        }

        return $value;

    }

    protected function sendError($text, $answer = '', $logMessage = null, $fileName = 'order_error_log.txt', $class = 'error'){

        $_SESSION['res']['answer'] = '<div class="' . $class . '">' . ($class === 'error' ? $this->translateEl($text) : $text) . ' ' . $answer . '</div>';

        if($logMessage){

            if(!is_string($logMessage)){

                $logMessage = $this->translateEl($text) . ' ' . $answer;

            }

            Logger::instance()->writeLog($logMessage, $fileName);

        }

        if($class === 'error'){

            $this->addSessionData();

        }

    }

    protected function sendSuccess($text, $answer = '', $logMessage = null, $fileName = 'order_error_log.txt', $class = 'success'){

        $this->sendError($text, $answer, $logMessage, $fileName, $class);

    }

}