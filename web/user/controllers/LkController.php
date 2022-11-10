<?php

namespace web\user\controllers;

class LkController extends BaseUser
{
    protected function actionInput(){

        if(!$this->userData){
            \AppH::redirect(\App::PATH());
        }

    }

}