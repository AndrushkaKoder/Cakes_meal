<?php

namespace webQApplication\controllers;

use webQTraits\TemplateOutputMethods;
use webQApplication\helpers\CartHelper;
use webQApplication\helpers\CatalogHelper;
use webQApplication\helpers\ValidationHelper;
use webQApplication\models\Model;

abstract class BaseUser extends \webQSystem\Controller
{
    use TemplateOutputMethods;
    use CatalogHelper;
    use CartHelper;
    use ValidationHelper;

    protected $model;
    protected $menu;

    protected function commonData(){

        $this->model = Model::instance();

        $this->getCartData();

        $this->menu = $this->model->get('catalog', [
            'where' => [
                'visible' => 1
            ],
            'join'=>[
                'goods' => [
                    'type' => 'inner',
                    'fields' => null,
                    'where' => [
                        'visible' => 1
                    ],
                    'on' => [
                        'id' => 'parent_id'
                    ]
                ]
            ],
            'order' => [
                'menu_position'
            ],
            'group' => 'catalog.id'

        ]);

         $this->set = $this->model->get('settings', [
             'limit' => 1,
             'single' => true
         ]);
    }

}