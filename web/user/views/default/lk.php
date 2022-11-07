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
                        <form action="#" method="post" class="lk_form">
                            <input type="text" name="name" id="lk_name" placeholder="Ваше имя" value="Аркадий">
                            <input type="text" name="surname" id="lk_surname" placeholder="Ваша фамилия" value="Залупкин">
                            <input type="number" name="phone_number" id="lk_phoneNumber" placeholder="Контактный номер" value="894235325243">
                            <input type="email" name="email" id="lk_email" placeholder="Электронная почта" value="sfsdf@yandex.ru">
                            <input type="text" name="birthday" id="birthday" placeholder="Дата рождения" value="01.01.2000">
                            <input type="password" name="password" id="password" placeholder="Введите новый пароль">
                            <input type="password" name="password_repeat" id="password" placeholder="Повторите пароль">
                            <button type="button" class="assortment_button">Сохранить</button>
                        </form>

                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="lk_wrapper_orders">
                        <p class="lk_wrapper_data_title text-center">Заказы</p>
                        <hr>

                        <div class="order_item">
                           <div class="order_item_date">
                               <p>01.01.2001</p>
                           </div>
                            <div class="order_item_position">
                                <p>Прага</p>
                                <p>3</p>
                                <p>100500Р</p>
                            </div>
                            <div class="order_item_position">
                                <p>Прага</p>
                                <p>3</p>
                                <p>100500Р</p>
                            </div>
                            <div class="order_item_position">
                                <p>Прага</p>
                                <p>3</p>
                                <p>100500Р</p>
                            </div>
                            <div class="order_total_sum">
                                <p> общая сумма: 228P</p>
                            </div>
                            <hr>
                        </div>
                        <div class="order_item">
                            <div class="order_item_date">
                                <p>01.01.2001</p>
                            </div>
                            <div class="order_item_position">
                                <p>Прага</p>
                                <p>3</p>
                                <p>100500Р</p>
                            </div>
                            <div class="order_item_position">
                                <p>Прага</p>
                                <p>3</p>
                                <p>100500Р</p>
                            </div>
                            <div class="order_item_position">
                                <p>Прага</p>
                                <p>3</p>
                                <p>100500Р</p>
                            </div>
                            <div class="order_total_sum">
                                <p> общая сумма: 228P</p>
                            </div>
                            <hr>
                        </div>
                        <div class="order_item">
                            <div class="order_item_date">
                                <p>01.01.2001</p>
                            </div>
                            <div class="order_item_position">
                                <p>Прага</p>
                                <p>3</p>
                                <p>100500Р</p>
                            </div>
                            <div class="order_item_position">
                                <p>Прага</p>
                                <p>3</p>
                                <p>100500Р</p>
                            </div>
                            <div class="order_item_position">
                                <p>Прага</p>
                                <p>3</p>
                                <p>100500Р</p>
                            </div>
                            <div class="order_total_sum">
                                <p> общая сумма: 228P</p>
                            </div>
                            <hr>
                        </div>
                        <div class="order_item">
                            <div class="order_item_date">
                                <p>01.01.2001</p>
                            </div>
                            <div class="order_item_position">
                                <p>Прага</p>
                                <p>3</p>
                                <p>100500Р</p>
                            </div>
                            <div class="order_item_position">
                                <p>Прага</p>
                                <p>3</p>
                                <p>100500Р</p>
                            </div>
                            <div class="order_item_position">
                                <p>Прага</p>
                                <p>3</p>
                                <p>100500Р</p>
                            </div>
                            <div class="order_total_sum">
                                <p> общая сумма: 228P</p>
                            </div>
                            <hr>
                        </div>
                        <div class="order_item">
                            <div class="order_item_date">
                                <p>01.01.2001</p>
                            </div>
                            <div class="order_item_position">
                                <p>Прага</p>
                                <p>3</p>
                                <p>100500Р</p>
                            </div>

                            <div class="order_total_sum">
                                <p> общая сумма: 228P</p>
                            </div>
                            <hr>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </section>


</main>