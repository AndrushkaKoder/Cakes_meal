<main>
<!--    КОРЗИНА   -->
    <div class="bread">
        <div class="container">
            <div class="row p-2">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= $this->alias()?>">На главную</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Корзина</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <section class="basket">

        <form action="<?=$this->alias('order')?>" method="POST" class="basketForm">
            <div class="container">

                <div class="row">

                    <div class="col-12">
                        <?php if(!empty($this->cart['goods']) && !empty($this->cart['total_sum'])):?>
                        <div class="basket_list">
                            <?php foreach ($this->cart['goods'] as $item):?>
                                    <div class="backet_item" data-productContainer>
                                        <img class="basket_item_img" src="<?=$this->img($item['img'])?>" alt="">
                                        <div class="backet_product_name">
                                            <a href="<?=$this->alias(['product'=>$item['alias']])?>" target="_blank"> <?=$item['name']?></a>
                                        </div>
                                        <div class="backet_product_counter">
                                            <button class="basket_counter_button minus" data-quantityMinus>-</button>
                                            <input class="basket_counter_input" data-quantity value="<?=$item['qty']?>">
                                            <button class="basket_counter_button plus" data-quantityPlus>+</button>
                                        </div>
                                        <div class="backet_product_price">
                                            <?=$item['price']?> &#8381;
                                        </div>
                                        <div class="backet_product_delete">
                                            <a href="<?=$this->alias(['cart'=>'remove', 'id'=>$item['id']])?>" class="basket_del"><i class="fa-solid fa-trash"></i></a>
                                        </div>
                                        <span data-addToCart="<?=$item['id']?>" data-toCartAdded style="display: none"></span>
                                    </div>
                                    <hr>

                             <?php endforeach;?>

                            <p class="text-center">общая сумма заказа: <span style="text-decoration: underline" data-totalSum><?=$this->cart['total_sum'] ?? 0?></span>&#8381; </p>
                        </div>
                    </div>

                </div>




                <!--  кнопка оформления  -->
                <div class="row">
                    <div class="col-12 d-flex justify-content-center mt-5">
                        <button type="button" class="basket_button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                           Оформить заказ
                        </button>
                    </div>
                </div>

                <?php else:?>
                    <div class="container">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p>В корзине пусто. Скорее добавьте сюда тортик!  	&#128579;</p>
                                <a href="<?=$this->alias('catalog')?>" class="cart_back_alias"><i class="fa-solid fa-arrow-left"></i> в меню</a>
                            </div>
                        </div>
                    </div>
                <?php endif;?>

                <!-- модалка заказа -->
                <?php if(!empty($this->cart['total_sum'])):?>
                <div class="modal fade" id="staticBackdrop"  data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Оформление заказа</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="basket_modal_wrapper">



                                    <div class="basket_modal_item text-center">
                                        <h5>Для кого</h5>
                                        <p class="basket_modal_descr">Ваши личные данные в надёжных руках</p>
                                        <div class="item_content item_content_name d-flex flex-column justify-content-center">
                                            <input type="text" name="name" id="userNameTotal" placeholder="Имя" required>
                                            <input type="text" name="phone" id="userPhoneTotal" placeholder="Телефон" required>
                                            <input type="email" name="email" placeholder="e-mail">
                                        </div>
                                    </div>

                                    <div class="basket_modal_item d-flex justify-content-center align-items-center flex-column">
                                        <h5>Дата</h5>
                                        <p class="basket_modal_descr">В какой день привезти Ваш заказ</p>
                                        <div class="item_content item_content_name">
                                            <input type="date" name="date_delivery" id="userDataTotal" style="width: 100%">
                                        </div>
                                    </div>

                                    <div class="basket_modal_item d-flex flex-column align-items-center">
                                        <h5>Куда Вам привезти?</h5>
                                        <p class="basket_modal_descr">Укажите Ваш адрес, и мы доставим заказ прямо в руки</p>
                                        <div class="item_content item_content_name">
                                        <input type="text" name="address" id="userAddressTotal" placeholder="Адрес доставки" style="width: 100%;">
                                        </div>
                                    </div>

                                    <?php if(!empty($this->payments)):?>
                                    <div class="basket_modal_item d-flex flex-column align-items-center">
                                        <h5>Оплата</h5>
                                        <p class="basket_modal_descr">Мы принимаем оплату в любой удобной форме</p>
                                        <select name="payments_id" id="userTotalPay">
                                            <?php foreach ($this->payments as $item):?>
                                                <option value="<?=$item['id']?>"><?=$item['name']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <?php endif;?>
<!--                                    <i class="fa-solid fa-plus"></i>-->
                                    <div class="bastet_modal_item basket_questions p-4">
                                        <p>
                                            <button class="basket_button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                Напишите нам свои пожелания

                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                        </p>
                                        <div class="collapse textarea_area" id="collapseExample">
                                            <div class="card card-body">
                                                <textarea name="comment" id="userComment" cols="10" rows="5" placeholder="Мы исполняем желания"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="basket_modal_item d-flex justify-content-around p-3">
                                        <p>Итого:</p>
                                        <p class="basket_total_price" data-totalSum> <?=$this->cart['total_sum']?> &#8381;</p>
                                    </div>

                                    <div class="backet_modal_item text-center">
                                       <div class="item_content d-flex justify-content-center align-items-center">
                                           <input type="checkbox" name="basket_privacy_check" id="basket_privacy_check" required checked>
                                           <p class="privacy_descr">Я согласен(согласна) на обработку персональных данных</p>
                                       </div>
                                        <p><a href="<?=$this->alias('privacy')?>" class="privacy_basket">Политика конфиденциальности</a></p>

                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">

                                <button type="submit" class="basket_button">Заказать</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif;?>
                <!-- модалка заказа -->


            </div>
        </form>

    </section>
</main>