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





//       $result = [];
//       $result['type_cake'] = $_POST['type_cake'];
//       $result['osnova'] = $_POST['osnova'];
//       $result['creme'] = $_POST['creme'];
//       $result['otdelka'] = $_POST['otdelka'];
//       $result['decor'] = $_POST['decor'];
//       $result['ves'] = $_POST['ves'];
//       $result['nadpis'] = $_POST['nadpis'];
//       $result['pozhelanie'] = $_POST['pozhelanie'];
//       $result['visitor_name'] = $_POST['userName'];
//       $result['visitor_phone'] = $_POST['userPhone'];
//       $result['date'] = $_POST['userData'];



//       foreach ($_POST as $item){
//           if(is_array($_POST[$item])){
//               json_encode($_POST[$item]);
//           }
//       }
//       if(is_array($_POST['otdelka']) && is_array($_POST['decor'])){
//          json_encode($_POST['otdelka']);
//          json_encode($_POST['decor']);
//       }

       $createOrder = \App::model()->add('custom_orders', [
           'return_query' => true
       ]);

       if(is_numeric($createOrder)){
           header("Location:".$this->alias(''));
       }

    $b=2;


     }


    }
}

