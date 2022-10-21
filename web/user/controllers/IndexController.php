<?php

namespace web\user\controllers;

use core\system\Controller;
use web\user\models\Model;

class IndexController extends BaseUser //будет наследоваться от другого класса. Пока. А тот класс будет extend от Controller
{

    protected function actionInput(){


        $sales = $this->model->get('sales');

        $tizzers = $this->model->get('tizzers');

        $assortment = $this->model->get('catalog', [
            'where' => [
                'visible' => 1
            ],
            'join' => [
                'goods' => [
                    'fields' => null, //данные о товаре
                    'type' => 'inner', // тип join
                    'on' => ['id'=>'parent_id'], // что вяжем
                    'where' => [ // условия
                        'hit' => 1,
                        'visible' => 1
                    ]
                ]
            ],
            'order' => 'menu_position', //сортировка по menu_position
            'group' => 'catalog.id' // группировка

        ]);

        $backgroundImage = $this->model->get('background_images');

        $questions = $this->model->get('questions', [
            'order'=>'menu_position'
        ]);

        return compact('sales', 'tizzers', 'assortment', 'backgroundImage', 'questions');

    }




}