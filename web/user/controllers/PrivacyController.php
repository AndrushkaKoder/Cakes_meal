<?php

namespace webQApplication\controllers;

class PrivacyController extends BaseUser
{
    protected function actionInput(){

        $contacts = $this->model->get('contacts');


        return compact('contacts');

    }

}