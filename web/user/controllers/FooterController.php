<?php

namespace web\user\controllers;

use web\user\models\Model;

class FooterController extends \core\system\Controller
{

    protected function actionInput(){
        $social = Model::instance()->getAll('socials');

        return compact('social');
    }
}