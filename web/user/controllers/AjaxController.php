<?php

namespace web\user\controllers;

class AjaxController extends BaseUser
{
    public $ajaxData = [];

    protected function actionInput(){

        $this->skipRenderingTemplates = true;

        if(!empty($_REQUEST)){
            $this->ajaxData = $_REQUEST;
        }

        if(!empty($_GET['ajax']) && $_GET['ajax'] === 'add_to_cart'){

            return $this->addToCart();


        }

    }

}