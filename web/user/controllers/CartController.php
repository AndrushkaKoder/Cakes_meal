<?php

namespace web\user\controllers;


use web\user\models\Model;

class CartController extends BaseUser
{

    protected $delivery;

    protected $payments;

    protected function actionInput()
    {


        if(!empty($this->parameters['alias'])){

            switch ($this->parameters['alias']){

                case 'delete':

                    if(!empty($this->parameters['id'])){

                        $this->deleteCartData($this->parameters['id'], $this->parameters['offers'] ?? null);

                    }else{

                        $this->clearCart();

                        $this->redirect();

                    }

                    break;

                case 'checkpayments':

                    if(method_exists($this, 'getPaymentStatus')){

                        (new OrderController())->getPaymentStatus();

                    }

                    break;

            }

        }

        $data = $this->createCartGoods();

        $moreData = null;

        if(method_exists($this, 'setProjectCartData')){

            $data = $this->setProjectCartData($data, $moreData);

        }

        if(in_array('delivery', $this->model->showTables())){

            $this->delivery = $this->model->get('delivery', [
                'where' => ['visible' => 1],
                'order' => ['menu_position'],
                'join_structure' => true
            ]);

        }

        if(in_array('payments', $this->model->showTables())){

            $this->payments = $this->model->get('payments', [
                'where' => ['visible' => 1],
                'order' => ['menu_position'],
                'join_structure' => true
            ]);

        }

        if(\AppH::isPost() && !empty($this->parameters[0]) && $this->parameters[0] === 'order'){

            (new OrderController())->order($data);

        }

        return compact('data', 'moreData');

    }

    public function createCartGoods(){

        if(empty($this->cart[$this->model->goodsTable])){

            return false;

        }

        $data = [];

        $replaceFields = ['qty', 'price', 'old_price', 'total_sum', 'total_old_sum'];

        foreach ($this->cart[\App::model()->goodsTable] as $item){

            $offers = [];

            if(!empty(\App::model()->offersTable) && !empty($item[\App::model()->offersTable])){

                $offers = $item[\App::model()->offersTable];

                unset($item[\App::model()->offersTable]);

            }

            if(!empty($item['qty'])){

                $data[] = $item;

            }

            if(!empty($offers)){

                foreach ($offers as $value){

                    $element = $item;

                    $element[\App::model()->offersTable] = $value;

                    if(!empty($replaceFields)){

                        foreach ($replaceFields as $field){

                            if(array_key_exists($field, $value)){

                                $element[$field] = $value[$field];

                            }

                        }

                    }

                    $data[] = $element;

                }

            }

        }

        return $data;

    }

}