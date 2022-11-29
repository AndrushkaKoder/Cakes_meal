<?php

namespace web\user\controllers;



class ConstructorController extends BaseUser
{

    protected function actionInput(){

     if(\AppH::isPost()){

         $translate = [
             'type_cake' => 'Тип изделия',
             'osnova' => 'Основа',
             'creme' => 'Крем',
             'otdelka' => 'Отделка',
             'decor' => 'Декор',
             'ves' => 'вес',
             'nadpis' => 'Надпись на торе',
             'pozhelanie' => 'Пожелание',
             'userName' => 'Имя',
             'userPhone' => 'Телефон',
             'userData' => 'Дата'
         ];

    $a=2;


       $createOrder = \App::model()->add('custom_orders', [
           'return_query' => true
       ]);

       if(is_numeric($createOrder)){
           $_SESSION['res']['answer'] = '<div class="success">Спасибо за заявку. Наш менеджер скоро свяжется с Вами</div>';
          \AppH::redirect($this->alias());
       }

    $b=2;


     }


    }
}

