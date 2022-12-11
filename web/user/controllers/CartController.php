<?php

namespace webQApplication\controllers;


use webQApplication\models\Model;

class CartController extends BaseUser
{

    protected $delivery;

    protected $payments;

    protected function actionInput()
    {


        if(!empty($this->parameters['alias'])){

            switch ($this->parameters['alias']){

                case 'remove':

                    if(!empty($this->parameters['id'])){

                        $this->deleteCartData($this->parameters['id'], $this->parameters['offers'] ?? null);

                    }else{

                        $this->clearCart();

                        \WqH::redirect($this->alias('cart'));

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

        if(\WqH::isPost() && !empty($this->parameters[0]) && $this->parameters[0] === 'order'){

            (new OrderController())->order($data);

        }

        return compact('data', 'moreData');

    }

    public function createCartGoods(&$cart = null){
        if(empty($this->cart)){
            $this->cart = $cart;
        }

        !$this->model && $this->model = Model::instance(); //костыль

        if(empty($this->cart[$this->model->goodsTable])){

            return false;

        }


        $data = [];

        $replaceFields = ['qty', 'price', 'old_price', 'total_sum', 'total_old_sum'];

        $m = \Wq::model();
        foreach ($this->cart[\Wq::model()->goodsTable] as $item){

            $offers = [];

            if(!empty(\Wq::model()->offersTable) && !empty($item[\Wq::model()->offersTable])){

                $offers = $item[\Wq::model()->offersTable];

                unset($item[\Wq::model()->offersTable]);

            }

            if(!empty($item['qty'])){

                $data[] = $item;

            }

            if(!empty($offers)){

                foreach ($offers as $value){

                    $element = $item;

                    $element[\Wq::model()->offersTable] = $value;

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