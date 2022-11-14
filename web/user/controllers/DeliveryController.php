<?php

namespace web\user\controllers;

class DeliveryController extends BaseUser
{

    protected function actionInput(){

        $information = $this->model->get('delivery_terms');

        return compact('information');
    }

}