<main>
<!--    КОРЗИНА   -->
    <div class="bread">
        <div class="container">
            <div class="row p-5">
                <div class="col-12 p-5">
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

        <form action="" method="POST" class="basketForm">
            <div class="container">

                <div class="row">

                    <div class="col-12">
                        <div class="backet_list">
                            <div class="backet_item">
                                <div class="backet_product_name">
                                    Торт прага
                                </div>
                                <div class="backet_product_counter">
                                    <button class="basket_counter_button minus">-</button>
                                    <input class="basket_counter_input" value="1">
                                    <button class="basket_counter_button plus">+</button>
                                </div>
                                <div class="backet_product_price">
                                    100500 &#8381;
                                </div>
                                <div class="backet_product_delete">
                                    <button class="basket_del"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div>
                            <hr>
                            <div class="backet_item">
                                <div class="backet_product_name">
                                    Торт прага
                                </div>
                                <div class="backet_product_counter">
                                    <button class="basket_counter_button minus">-</button>
                                    <input class="basket_counter_input" value="1">
                                    <button class="basket_counter_button plus">+</button>
                                </div>
                                <div class="backet_product_price">
                                    100500 &#8381;
                                </div>
                                <div class="backet_product_delete">
                                    <button class="basket_del"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div>
                            <hr>
                            <div class="backet_item">
                                <div class="backet_product_name">
                                    Торт прага
                                </div>
                                <div class="backet_product_counter">
                                    <button class="basket_counter_button minus">-</button>
                                    <input class="basket_counter_input" value="1">
                                    <button class="basket_counter_button plus">+</button>
                                </div>
                                <div class="backet_product_price">
                                    100500 &#8381;
                                </div>
                                <div class="backet_product_delete">
                                    <button class="basket_del"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div>
                            <hr>
                            <div class="backet_item">
                                <div class="backet_product_name">
                                    Торт прага
                                </div>
                                <div class="backet_product_counter">
                                    <button class="basket_counter_button minus">-</button>
                                    <input class="basket_counter_input" value="1">
                                    <button class="basket_counter_button plus">+</button>
                                </div>
                                <div class="backet_product_price">
                                    100500 &#8381;
                                </div>
                                <div class="backet_product_delete">
                                    <button class="basket_del"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>

                </div>


                <!--  кнопка оформления  -->
                <div class="row">
                    <div class="col-12 d-flex justify-content-center mt-5">
                        <button type="button" class="constructor_button_offer" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                           Оформить заказ
                        </button>
                    </div>
                </div>


                <!-- модалка заказа -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                                            <input type="text" name="userNameTotal" id="userNameTotal" placeholder="Имя" required>
                                            <input type="text" name="userPhoneTotal" id="userPhoneTotal" placeholder="Телефон" required>
                                        </div>
                                    </div>

                                    <div class="basket_modal_item d-flex justify-content-center align-items-center flex-column">
                                        <h5>Дата</h5>
                                        <p class="basket_modal_descr">В какой день привезти Ваш заказ</p>
                                        <div class="item_content item_content_name">
                                            <input type="date" name="userDataTotal" id="userDataTotal" style="width: 100%">
                                        </div>
                                    </div>

                                    <div class="basket_modal_item d-flex flex-column align-items-center">
                                        <h5>Куда Вам привезти?</h5>
                                        <p class="basket_modal_descr">Укажите Ваш адрес, и мы доставим заказ прямо в руки</p>
                                        <div class="item_content item_content_name">
                                        <input type="text" name="userAddressTotal" id="userAddressTotal" placeholder="Адрес доставки" style="width: 100%;" required>
                                        </div>
                                    </div>

                                    <div class="basket_modal_item d-flex flex-column align-items-center">
                                        <h5>Оплата</h5>
                                        <p class="basket_modal_descr">Мы принимаем оплату в любой удобной форме</p>
                                        <select name="userTotalDelivery" id="userTotalDelivery">
                                            <option value="payCard">Банковская карта</option>
                                            <option value="payCash">Наличные</option>
                                            <option value="payTranslation">Перевод</option>
                                        </select>
                                    </div>

                                    <div class="basket_modal_item d-flex justify-content-around p-3">
                                        <p>Итого:</p>
                                        <p class="basket_total_price"> 100500 &#8381;</p>
                                    </div>

                                    <div class="backet_modal_item text-center">
                                       <div class="item_content d-flex justify-content-center align-items-center">
                                           <input type="checkbox" name="basket_privacy_check" id="basket_privacy_check">
                                           <p class="privacy_descr">Я согласен(согласна) на обработку персональных данных</p>
                                       </div>
                                        <p><a href="<?=$this->alias('privacy')?>" class="privacy_backet">Политика конфиденциальности</a></p>

                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">

                                <button type="submit" class="constructor_button_offer">Заказать</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- модалка заказа -->


            </div>
        </form>

    </section>
</main>