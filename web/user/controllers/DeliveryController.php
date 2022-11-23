<?php

namespace web\user\controllers;

class DeliveryController extends BaseUser
{

    protected function actionInput(){

        $contacts = $this->model->get('contacts');

        $information = $this->model->get('delivery_terms');

        return compact('information', 'contacts');
    }

}