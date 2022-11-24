<?php

namespace web\user\controllers;

class ConstructorController extends BaseUser
{

    protected function actionInput(){

     if(\AppH::isPost()){

         if($_POST['type_cake'] === 'Бисквит' || $_POST['type_cake'] === 'Бенто'){
             $_POST['osnova'] = $_POST['osnova_bisquit'];
             $_POST['creme'] = $_POST['creme_bisquit'];
             unset($_POST['osnova_muss']);
             unset($_POST['creme_muss']);
             unset($_POST['osnova_bisquit']);
             unset($_POST['creme_bisquit']);



         } else{
             $_POST['osnova'] = $_POST['osnova_muss'];
             $_POST['creme'] = $_POST['creme_muss'];
             unset($_POST['osnova_muss']);
             unset($_POST['osnova_bisquit']);
             unset($_POST['creme_bisquit']);
             unset($_POST['creme_muss']);
         }

         if($_POST['type_cake'] === 'Бенто'){
             $_POST['ves'] = $_POST['weight_bento'];
             unset($_POST['weight']);

         } else{
             $_POST['ves'] = $_POST['weight'];
             unset($_POST['weight_bento']);
         }



         $otdelka = [
             $_POST['otdelkaMastika'],
             $_POST['otdelkaCreme'],
             $_POST['otdelkaFruits']
         ];

         $decor = [
             $_POST['decorStruzhka'],
            $_POST['decorNuts'],
            $_POST['decorMarshmellow'],
            $_POST['decorBeze']
         ];

         $_POST['otdelka'] = array_diff($otdelka, array(null)) ;
         $_POST['decor'] = array_diff($decor, array(null));



         $a=1;



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


         //пройти циклом по массиву $translate и создать письмо


       $result = [];



       $result['Тип изделия'] = $_POST['type_cake'];
       $result['Основа'] = $_POST['osnova'];
       $result['Крем'] = $_POST['creme'];
       $result['отделка'] = $_POST['otdelka'];
       $result['декор'] = $_POST['decor'];
       $result['вес'] = $_POST['ves'];
       $result['надпись'] = $_POST['nadpis'];
       $result['пожелание'] = $_POST['pozhelanie'];
       $result['имя'] = $_POST['userName'];
       $result['телефон'] = $_POST['userPhone'];
       $result['дата'] = $_POST['userData'];


    $b=2;


     }


    }
}

