<?php

namespace web\user\controllers;

use core\exceptions\RouteException;
use core\system\Controller;
use web\user\models\Model;

class ProductController extends BaseUser
{

    protected function actionInput(){



        if(empty($this->parameters[0])){
            throw new RouteException('Такой страницы не существует');
        }

     $product = $this->getGoods([
         'where' => [
             'visible' => 1,
             'alias' => $this->parameters[0]
         ],
         'single' => true
     ]);

        return compact('product');

    }



}


//$data = [
//    'id' => 1,
//    'name' => 'praga',
//    'filers' => [
//        'тип изделия' => [
//            'бисквитный торт'
//        ],
//        'начинка' => [
//            'фруктовая',
//            'ягодная'
//        ]
//    ]
//]