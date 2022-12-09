<?php

namespace webQApplication\controllers;

use webQExceptions\RouteException;
use webQSystem\Controller;
use webQApplication\models\Model;

class ProductController extends BaseUser
{

    protected function actionInput(){

    if(empty($this->parameters['alias'])){

        throw new RouteException('Такой страницы не существует');

    }

     $product = $this->getGoods([
         'where' => [
             'visible' => 1,
             'alias' => $this->parameters['alias']
         ],
         'single' => true
     ]);
        $a=1;
        return compact('product');

    }



}