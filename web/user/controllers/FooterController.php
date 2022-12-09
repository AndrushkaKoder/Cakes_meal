<?php

namespace webQApplication\controllers;

use webQApplication\models\Model;

class FooterController extends \webQSystem\Controller
{

    protected function actionInput(){
        $social = Model::instance()->getAll('socials');

        return compact('social');
    }
}