<?php

namespace web\user\controllers;

use core\traites\TemplateOutputMethods;
use web\user\helpers\CartHelper;
use web\user\helpers\CatalogHelper;
use web\user\helpers\ValidationHelper;
use web\user\models\Model;

abstract class BaseUser extends \core\system\Controller
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
        $a = 1;
    }

}