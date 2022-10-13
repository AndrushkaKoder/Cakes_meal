<?php

namespace web\user\controllers;

use core\system\Controller;
use web\user\models\Model;

class ProductController extends BaseUser
{

    protected function actionInput(){



//        $goods = $this->model->get('goods');




        return compact('goods');

    }

}