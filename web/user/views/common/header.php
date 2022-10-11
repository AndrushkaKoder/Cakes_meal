<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Ms+Madi&family=Playfair+Display:ital,wght@1,500&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
    <?php $this->getStyles()?>
    <link rel="shortcut icon" href="/images/favicon2.png" type="image/x-icon">
    <title>Cakes Meal Калуга</title>
</head>

<?php
$headerClass = $this->getController() === 'index' ? 'index' : '';

?>
<!---->
<body style="background:url(<?=$this->getTemplateImg()?>bg/bg1.jpg)">
<!-- хедер -->
<header  class="<?=$headerClass?> header" data-header style="position: absolute; top: 0; left: 0; z-index: 10; background: transparent">

    <div class="container">
        <div class="row">

            <div class="col-lg-3 col-md-12 col-sm-12 d-flex justify-content-center align-items-center">
                <a href="<?=$this->alias()?>" class="cakes_logo">Cakes Meal</a>
            </div>
            <div class="col-lg-7 col-md-10 col-sm-12 mobile_none">
                <ul class="header_nav_list">
                    <li class="header_nav_list_item"><a href="<?=$this->alias()?>">Главная</a></li>
                    <?php if(!empty($this->menu)):?>
                        <li class="header_nav_list_item dropdownWrapper">
                            <a class="dropdown" id="no_border" href="<?=$this->alias('catalog')?>" role="button"  aria-expanded="false">Ассортимент</a>
                            <ul class="dropdown-menu">

                                <?php foreach ($this->menu as $item):?>
                                    <li><a class="dropdown-item" href="<?=$this->alias(['catalog'=>$item['alias']])?>"><?=$item['name']?></a></li>
                                <?php endforeach;?>

                            </ul>
                        </li>
                    <?php endif;?>

                    <li class="header_nav_list_item"><a href="<?=$this->alias('constructor')?>">Конструктор</a></li>
                    <li class="header_nav_list_item"><a href="<?=$this->alias('about')?>">О нас</a></li>
                    <li class="header_nav_list_item"><a href="#footer">Контакты</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 d-flex justify-content-center align-items-center" data-nav_wrapper style="position: relative">

                <a href="tel:89308478453" class="nav_item  nav_phone"><i class="fa-solid fa-phone-flip"></i></a>
                <a href="<?=$this->alias('cart')?>" class="nav_item nav_bucket"> <i class="fa-solid fa-cart-shopping"></i></a>
                <a href="#" class="nav_item" data-burger_button><i class="fa-solid fa-bars burger_nav_button"></i></a>
<!--                <a href="" class="nav_item"><i class="fa-solid fa-magnifying-glass"></i></a>-->
                <a href="" class="nav_item"><i class="fa-solid fa-magnifying-glass search_button"></i></a>
            </div>
            <div class="col-12">
                <form action="" method="GET" class="search_wrapper hideSearch d-flex">
                    <div class="search_area">
                        <input type="text" id="search_area" class="">
                        <button type="submit"><i class="fa-solid fa-magnifying-glass search_button"></i></button>
                    </div>

                </form>
            </div>

        </div>
    </div>

</header>




<!-- burger section -->
<div class="burger_section" data-burger_menu>

    <div class="burger_section_wrapper">
        <div class="cross" data-cross><i class="fa-solid fa-circle-xmark cross_button"></i></div>
    </div>
    <div class="burger_section_content">
        <ul class="burger_section_content_list">
            <li class="header_nav_list_item"><a href="<?=\App::PATH()?>index">Главная</a></li>
            <li class="header_nav_list_item"> <a class="dropdown-toggle" href="<?=\App::PATH()?>assortment" role="button" data-bs-toggle="dropdown" aria-expanded="false">Ассортимент</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Бисквитные торты</a></li>
                    <li><a class="dropdown-item" href="#">Муссовые торты</a></li>
                    <li><a class="dropdown-item" href="#">Бенто торты</a></li>
                    <li><a class="dropdown-item" href="#">Капкейки</a></li>
                    <li><a class="dropdown-item" href="#">Трайфлы</a></li>
                    <li><a class="dropdown-item" href="#">Меренга</a></li>
                </ul>
            </li>
            <li class="header_nav_list_item"><a href="<?=\App::PATH()?>constructor">Конструктор</a></li>
            <li class="header_nav_list_item"><a href="<?=\App::PATH()?>about">О нас</a></li>
            <li class="header_nav_list_item"><a href="#footer">Контакты</a></li>
        </ul>
    </div>

</div>
<!-- burger section -->
