<?php

namespace web\user\controllers;

class AjaxController extends \core\system\Controller
{

    protected function actionInput(){

        $this->skipRenderingTemplates = true;

        if(empty($_COOKIE['visitor']) || empty($_GET)){

            return['success' => 0, 'message' => 'Не балуйтесь'];

        }

        $method = 'add';

        foreach (['add', 'edit', 'delete'] as $item){

            if(!empty($_GET[$item])){

                $method = $item;

                break;

            }

        }

        $_GET['comment'] = \AppH::clearStr($_GET['comment'] ?? '');

        if(empty($_GET['comment'])){

            if($method !== 'delete'){

                return['success' => 0, 'message' => 'Заполните текст комментария'];

            }

        }

        $method .= 'Comment';

        return \web\user\models\Model::instance()->$method();

    }

}