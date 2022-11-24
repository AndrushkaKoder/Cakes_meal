<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Ms+Madi&family=Playfair+Display:ital,wght@1,500&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
    <?php $this->getStyles()?>
    <link rel="shortcut icon" href="/web/user/views/default/images/fav.png" type="image/x-icon">
    <title>Cakes Meal Калуга</title>
</head>

<?php
$headerClass = $this->getController() === 'index' ? 'index' : '';

$display = $this->getController() === 'login' ? 'none' : 'block';
?>
<!---->
<body style="background:url(<?=$this->getTemplateImg()?>bg/bg1.jpg)">
<!-- хедер -->


<header  class="<?=$headerClass?> header" data-header style="position: absolute; top: 0; left: 0; z-index: 10; background: transparent; display: <?=$display?>">

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
                            <a class="dropdown-toggle" id="no_border" href="<?=$this->alias('catalog')?>" role="button"  aria-expanded="false">Ассортимент</a>
                            <ul class="dropdown-menu">

                                <?php foreach ($this->menu as $item):?>
                                    <li><a class="dropdown-item" href="<?=$this->alias(['catalog'=>$item['alias']])?>"><?=$item['name']?></a></li>
                                <?php endforeach;?>

                            </ul>
                        </li>
                    <?php endif;?>

                    <li class="header_nav_list_item"><a href="<?=$this->alias('constructor')?>">Конструктор</a></li>
                    <li class="header_nav_list_item"><a href="<?=$this->alias('about')?>">О нас</a></li>
                    <li class="header_nav_list_item"><a href="<?=$this->alias('delivery')?>">Доставка</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 d-flex justify-content-center align-items-center" data-nav_wrapper style="position: relative">

                <a href="tel:89308478453" class="nav_item  nav_phone"><i class="fa-solid fa-phone-flip"></i></a>
                <a href="<?=$this->alias('cart')?>" class="nav_item nav_bucket"> <i class="fa-solid fa-cart-shopping"></i> <span class="cart_counter" data-totalQTY><?=$this->cart['total_qty'] ?? 0?></span></a>
                <a href="#" class="nav_item" style="display: none" data-burger_button><i class="fa-solid fa-bars burger_nav_button"></i></a>

                <a href="" class="nav_item search_button"><i class="fa-solid fa-magnifying-glass search_button"></i></a>


                <!--попап регистрации-->
                <?php if(!$this->userData):?>
                <button type="button" class="nav_item lk_button" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                    <i class="fa-solid fa-user"></i>
                </button>
                <?php else:?>
                    <a href="<?=$this->alias('lk')?>" class="nav_item search_button"> <i class="fa-solid fa-user"></i></a>
                <?php endif;?>
                <div class="modal fade login_registration"  id="exampleModal1" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header lk_popUp_header">
                               <div class="lk_popUp_titles d-flex justify-content-evenly" style="width: 100%">
                                   <span class="modal-title fs-5" style="cursor: pointer" id="exampleModalLabel">Войти в аккаунт</span>
                                   <span class="modal-title fs-5" style="cursor: pointer" id="exampleModalLabel">Регистрация</span>
                               </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <!--форма авторизации-->
                                <form action="<?=$this->alias(['login'=>'login'])?> " method="post">
                                    <div class="mb-3">
                                        <input type="text" name="login" class="form-control lk_popUp_input" placeholder="Телефон или e-mail">
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" name="password" class="form-control lk_popUp_input" placeholder="Пароль">
                                    </div>


                                    <div class="m-3 text-center">
                                        <button type="submit" class="assortment_button">Войти</button>
                                    </div>
                                </form>
                                <!--форма авторизации-->


                                <!--форма регистрации-->
                                <form action="<?=$this->alias(['login'=>'registration'])?> " method="post" class="register_form" style="display: none">
                                    <div class="mb-3">
                                        <p class="login_description text-center">Имя</p>
                                        <input type="text" class="form-control" name="name" placeholder="Имя" required value="">
                                    </div>
                                    <div class="mb-3">
                                        <p class="login_description text-center">Телефон</p>
                                        <input type="tel" class="form-control" name="phone" required placeholder="Телефон">
                                    </div>
                                    <div class="mb-3">
                                        <p class="login_description text-center">E-mail</p>
                                        <input type="email" class="form-control" name="email" required placeholder="E-mail">
                                    </div>
                                    <div class="mb-3">
                                        <p class="login_description text-center">Дата рождения</p>
                                        <input type="date" class="form-control" name="birthday" id="birthday" placeholder="Это для подарка Вам">
                                    </div>
                                    <div class="mb-3">
                                        <p class="login_description text-center">Пароль</p>
                                        <input type="password" class="form-control" name="password"  required placeholder="Пароль">
                                    </div>
                                    <div class="mb-3">
                                        <p class="login_description text-center">Повторите пароль</p>
                                        <input type="password" class="form-control" name="confirm_password" required placeholder="Повторите пароль">
                                    </div>


                                    <label for="data_confirm" class="m-3 text-center" data-confirm>
                                        <input id="data_confirm" type="checkbox" required checked>
                                        <p class="privacy_descr">Я согласен(согласна) на обработку <a href="<?=$this->alias('privacy')?>" class="privacy_basket" target="_blank"> персональных данных</a></p>
                                    </label>
                                    <button type="submit" class="assortment_button">Регистрация</button>
                                </form>
                                <!--форма регистрации-->


                            </div>

                        </div>
                    </div>
                </div>
                <!--попап регистрации-->

            </div>
            <div class="col-12 position-relative">
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
            <li class="header_nav_list_item"><a href="<?=$this->alias()?>">Главная</a></li>
            <li class="header_nav_list_item"> <a class="dropdown-toggle" href="<?=$this->alias('catalog')?>" data-bs-toggle="dropdown" aria-expanded="false">Ассортимент</a>
                <ul class="dropdown-menu">
                    <?php foreach ($this->menu as $item):?>
                        <li><a class="dropdown-item" href="<?=$this->alias(['catalog'=>$item['alias']])?>"><?=$item['name']?></a></li>
                    <?php endforeach;?>
                </ul>
            </li>
            <li class="header_nav_list_item"><a href="<?=$this->alias('constructor')?>">Конструктор</a></li>
            <li class="header_nav_list_item"><a href="<?=$this->alias('about')?>">О нас</a></li>
            <li class="header_nav_list_item"><a href="<?=$this->alias('delivery')?>">Доставка</a></li>
        </ul>
    </div>

</div>
<!-- burger section -->
