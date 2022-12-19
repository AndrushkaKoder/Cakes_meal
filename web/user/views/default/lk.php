<main>
    <div class="bread">
        <div class="container">
            <div class="row p-2">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= $this->alias()?>">На главную</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Личный кабинет</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <section>
        <div class="container">
            <div class="row">
                <div class="col-12 text-center p-2">
                    <h2>Личный кабинет</h2>
                </div>
            </div>


            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="lk_wrapper_data">
                        <p class="lk_wrapper_data_title text-center">Личные данные</p>
                        <form action="<?=$this->alias(['login'=>'registration'])?>" method="post" class="lk_form">
                            <input type="text" name="name" placeholder="Ваше имя" value="<?=$this->setFormValues('name', 'userData')?>">
                            <input type="tel" name="phone" placeholder="Контактный номер" value="<?=$this->setFormValues('phone', 'userData')?>">
                            <input type="email" name="email" placeholder="Электронная почта" value="<?=$this->setFormValues('email', 'userData')?>">
                            <input type="text" name="birthday" id="birthday" placeholder="Дата рождения" value="<?=$this->setFormValues('birthday', 'userData')?>">
                            <input type="password" name="password" placeholder="Введите новый пароль">
                            <input type="password" name="confirm_password" placeholder="Повторите пароль">
                            <button type="submit" class="assortment_button">Сохранить</button>
                        </form>

                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="lk_wrapper_orders">
                        <p class="lk_wrapper_data_title text-center">Заказы</p>
                        <hr>

                        <?php if(!empty($orders)):?>
                            <?php foreach ($orders as $item):?>

                                <div class="order_item">
                                    <div class="total_sum">
                                        <strong>Заказ №</strong>: <?=$item['id']?>
                                    </div>
                                    <div class="total_sum">
                                      <strong>общая сумма заказа</strong>: <?=$item['total_sum']?> &#8381;
                                    </div>
                                    <div class="total_sum">
                                        <strong>всего товаров</strong>: <?=$item['total_qty']?>
                                    </div>
                                    <div class="order_item_date">
                                        <strong>дата заказа</strong>: <?=$item['date']?>
                                    </div>
                                    <?php if(!empty($item['comment'])):?>
                                        <div class="order_item_date">
                                            <strong>комментарий к заказу</strong>: <?=$item['comment']?>
                                        </div>
                                    <?php endif;?>

                                    <?php if(!empty($item['join']['delivery'])):?>
                                        <div class="order_item_date">
                                            <strong>Доставка</strong>: <?=$item['join']['delivery']['name']?>
                                        </div>
                                    <?php endif;?>
                                    <?php if(!empty($item['join']['payments'])):?>
                                        <div class="order_item_date">
                                            <strong>Оплата</strong>: <?=$item['join']['payments']['name']?>
                                        </div>
                                    <?php endif;?>

                                    <div class="order_item_date">
                                        <strong>Статус заказа</strong>: <?=$item['join']['orders_statuses']['name']?>
                                    </div>

                                        <h4 class="text-center m-3">Заказанные товары</h4>

                                    <?php foreach ($item['join']['orders_goods'] as $goods):?>
                                        <div class="order_item_position">
                                            <p><?=$goods['name']?></p>
                                            <p><?=$goods['qty']?></p>
                                            <p><?=$goods['price']?></p>
                                        </div>
                                    <?php endforeach;?>
                                    <?php if(!empty($item['gift'])):?>
                                        <div class="order_item_position" style="align-items: flex-end">
                                            <p><strong>подарок:</strong> <br> <?=$item['gift']?></p>
                                            <p>1</p>
                                            <p>0</p>
                                        </div>
                                    <?php endif;?>



                                    <hr>
                                </div>
                            <?php endforeach;?>
                        <?php else:?>
                        <div class="goToCatalog">
                            <p>Скорее оформите заказ &#128525;</p>
                            <a href="<?=$this->alias('catalog')?>" class="cart_back_alias"><i class="fa-solid fa-arrow-left"></i> в меню</a>
                        </div>

                        <?php endif;?>


                    </div>
                </div>
            </div>
            <!-- кнопка выйти -->
            <div class="row">
                <form action="" method="POST">
                    <div class="col-12 d-flex justify-content-center pt-5">
                        <button onclick="location.href='<?=$this->alias(['login'=>'logout'])?>'" class="btn btn-danger lk_button_exit p-2" type="button" name="exit">Выйти</button>
                    </div>
                </form>

            </div>
        </div>
    </section>


</main>