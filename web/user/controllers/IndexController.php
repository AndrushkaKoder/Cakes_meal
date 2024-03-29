<?php

namespace webQApplication\controllers;

use webQSystem\Controller;
use webQSystem\Logger;
use webQApplication\models\Model;

class IndexController extends BaseUser //будет наследоваться от другого класса. Пока. А тот класс будет extend от Controller
{


    protected function actionInput(){

        $this->skipRenderingTemplates = false; // true - отдавать данные в JSON без шаблонизации (для VUE/REACT)

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



        if(!empty($_POST['phoneLogin'] && !empty($_POST['passwordLogin']))){
            $phoneLogin = $_POST['phoneLogin'];
            $passwordLogin = md5($_POST['passwordLogin']);

            $login = $this->model->get('users', [
                'where'=>[
                    'phone' => $phoneLogin,
                    'password' => $passwordLogin
                ]
            ]);

        }

        return compact('sales', 'tizzers', 'assortment', 'backgroundImage', 'questions', 'login');

    }


}