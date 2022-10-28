<?php

namespace web\user\controllers;

class CartController extends BaseUser
{

    protected $payments;
    protected $delivery;


    protected function actionInput(){
        if(!empty($_POST)){
            foreach ($_POST as $item=>$value){
                $_POST[$item] = \AppH::clearStr($value);
                $this->model->add('user_delivery', $_POST);
            }
        }
        if($this->parameters[0] === 'remove' && !empty($this->parameters[2])){
            $this->deleteCartData($this->parameters[2]);
        }


        $this->payments = $this->model->get('payments',[
            'where'=>['visible' => 1],
            'order' => 'menu_position',

        ]);


    $a=1;

    }
}