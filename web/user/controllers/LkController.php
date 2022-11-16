<?php

namespace web\user\controllers;

class LkController extends BaseUser
{
    protected function actionInput()
    {

        if (!$this->userData) {
            \AppH::redirect(\App::PATH());
        }


        $freeAccess = ['delayed'];

        if (!$this->userData) {

            if (!$freeAccess || empty($this->parameters['alias']) || !in_array($this->parameters['alias'], $freeAccess)) {

                \AppH::redirect($this->alias());

            }

        }

        $orders = $currentOrder = $delayed = $wishList = null;

        if ($this->userData) {

            $orders = $this->model->get('orders', [
                'where' => ['visitors_id' => $this->userData['id']],
                'order' => 'date DESC',
                'join' => [
                    'orders_goods' => [
                        'on' => ['id' => 'orders_id']
                    ],
                    'payments' => [
                        'on' => [
                            'table' => 'orders',
                            'fields' => ['payments_id' => 'id']
                        ],
                        'single' => true
                    ],
                    'delivery' => [
                        'on' => [
                            'table' => 'orders',
                            'fields' => ['delivery_id' => 'id']
                        ],
                        'single' => true
                    ],
                    'orders_statuses' => [
                        'on' => [
                            'table' => 'orders',
                            'fields' => ['orders_statuses_id' => 'id']
                        ],
                        'single' => true
                    ],
                ],
                'join_structure' => true
            ]);

            if ($orders) {



                if (!empty($this->parameters['current-order'])) {

                    $id = \AppH::clearNum($this->parameters['current-order']);

                    $currentOrder = $orders[$id] ?? null;

                }

            }

        }

        if(!empty($this->parameters['alias'])){

            switch($this->parameters['alias']){

                case 'delayed':

                    $delayed = $this->getDelayed();

                    $this->template = TEMPLATE . 'lk_delayed';

                    break;


                case 'restate':

                    $this->restateOrder($orders ?? null);

                    break;

            }

        }

        return compact('orders', 'currentOrder', 'delayed', 'wishList');

    }


}