    <main>
    <div class="bread">
        <div class="container">
            <div class="row p-2">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=$this->alias()?>">На главную</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Конструктор</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>



    <!-- конструктор -->

    <section class="constructor_title">
        <img src="/web/user/views/default/images/bg/choco.jpg" class="constructor_img" alt="#">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="constructor_title_tile text-center mb-5">Собери свой торт сам</h2>
                    <p class="constructor_descr">Выберите основу, начинку, а так же покрытие и дополнения для вашего торта.
                        Мы учтём все предпочтения и свяжемся с вами для обсуждения деталей заказа.
                        Время изготовления любого торта 3 дня с даты подтверждения заказа.
                    </p>
                </div>
            </div>
        </div>

    </section>


    <section class="section_constructor">

        <div class="container">

            <form action="<?=$this->alias('constructor')?>" method="POST" class="form_constructor" data-constructor>
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12">

                        <div class="constructor_wrapper d-flex justify-content-center flex-column align-items-center">

                            <!-- ТИП ТОРТА -->
                            <div class="constructor_item" data-type_cake>
                                <h2 class="item_title">Тип торта</h2>
                                <p class="constructor_subtitile"></p>
                                <div class="item_content d-flex">
                                    <div class="item_label">
                                        <input type="radio" id="type__bisquit" name="type_cake" class="type_cake bisquit" value="Бисквит" data-bisquit_cake checked>
                                        <label for="type__bisquit">Бисквит</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" id="type__muss" name="type_cake" class="type_cake muss" value="Мусс" data-muss_cake>
                                        <label for="type__muss">Мусс</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio"  id="type__bento" name="type_cake" class="type_cake bisquit" value="Бенто" data-bento_cake>
                                        <label for="type__bento">Бенто</label>
                                    </div>
                                </div>
                            </div>

                            <!-- ОСНОВА БИСКВИТ/БЕНТО -->
                            <div class="constructor_item" data-osnova_bisquit>
                                <h5 class="item_title">Основа</h5>
                                <div class="item_content d-flex">
                                    <div class="item_label">
                                        <input type="radio" name="osnova" id="type__white__bisquit" class="type_cake" value="Белый Бисквит" >
                                        <label for="type__white__bisquit">Белый бисквит</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" name="osnova" id="type__smetana__bisquit" class="type_cake" value="Бисквит на сметане">
                                        <label for="type__smetana__bisquit">Бисквит на сметане</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" name="osnova" id="type__smetana__choko__bisquit" class="type_cake" value="Шоколад на сметане">
                                        <label for="type__smetana__choko__bisquit">Шоколад на сметане</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" name="osnova" id="type__honey__bisquit" class="type_cake" value="медовый">
                                        <label for="type__honey__bisquit">медовый</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" name="osnova" id="type__nuts__bisquit" class="type_cake" value="Ореховый">
                                        <label for="type__nuts__bisquit">Ореховый</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" name="osnova" id="type__choko__bisquit" class="type_cake" value="Шоколад">
                                        <label for="type__choko__bisquit">Шоколад</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" name="osnova" id="type__sand__bisquit" class="type_cake" value="Песочный">
                                        <label for="type__sand__bisquit">Песочный</label>
                                    </div>
                                </div>
                            </div>


                            <!-- ОСНОВА МУСС -->
                            <div class="constructor_item" data-osnova_muss>

                                <h5 class="item_title">Основа</h5>
                                <div class="item_content d-flex">
                                    <div class="item_label">
                                        <input type="radio" name="osnova" id="osnova__tri_choko__muss" class="type_cake" value="Три шоколада" >
                                        <label for="osnova__tri_choko__muss">Три шоколада</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" name="osnova"  id="osnova__dva_choko__muss" class="type_cake" value="Два шоколада">
                                        <label for="osnova__dva_choko__muss">Два шоколада</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" name="osnova"  id="osnova__cheese__muss" class="type_cake" value="Крем-чиз">
                                        <label for="osnova__cheese__muss">Крем-чиз</label>
                                    </div>
                                </div>
                            </div>


                            <!-- КРЕМ -->
                            <div class="constructor_item" data-creme_bisquit>
                                <h5 class="item_title">Крем</h5>
                                <div class="item_content d-flex">
                                    <div class="item_label">
                                        <input type="radio" name = "creme" id="krem__maslyanny__bisquit" class="type_cake" value="Масляный" >
                                        <label for="krem__maslyanny__bisquit">Масляный</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" name = "creme" id="krem__smetanny__bisquit" class="type_cake" value="Сметанный">
                                        <label for="krem__smetanny__bisquit">Сметанный</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" name = "creme" id="krem__shokoladny__bisquit" class="type_cake" value="Шоколадный">
                                        <label for="krem__shokoladny__bisquit">Шоколадный</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" name = "creme" id="krem__zavarnoy__bisquit" class="type_cake" value="Заварной">
                                        <label for="krem__zavarnoy__bisquit">Заварной</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" name = "creme" id="krem__chizz__bisquit" class="type_cake" value="Крем-чиз">
                                        <label for="krem__chizz__bisquit">Крем-чиз</label>
                                    </div>
                                </div>
                            </div>


                            <!-- КРЕМ МУСС -->
                            <div class="constructor_item" data-creme_muss>
                                <h5 class="item_title">Наполнитель для мусса</h5>
                                <div class="item_content d-flex">
                                    <div class="item_label">
                                        <input type="radio" name = "creme" id="napolnitel__caramel__muss" class="type_cake" value="Карамель" >
                                        <label for="napolnitel__caramel__muss">Карамель</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" name = "creme" id="napolnitel__klubnika__muss" class="type_cake" value="Клубника">
                                        <label for="napolnitel__klubnika__muss">Клубника</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="radio" name = "creme" id="napolnitel__abricos__muss" class="type_cake" value="Абрикос">
                                        <label for="napolnitel__abricos__muss">Абрикос</label>
                                    </div>
                                </div>
                            </div>


                            <!-- ОТДЕЛКА -->
                            <div class="constructor_item" data-otdelka>
                                <h5 class="item_title">Отделка</h5>
                                <div class="item_content d-flex">
                                    <div class="item_label">
                                        <input type="checkbox" name = "otdelka[mastika]" id="otdelka__mastika__bisquit" class="type_cake" value="Мастика" checked>
                                        <label for="otdelka__mastika__bisquit">Мастика</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="checkbox" name = "otdelka[creme]" id="otdelka__krem__bisquit" class="type_cake" value="Крем">
                                        <label for="otdelka__krem__bisquit">Крем</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="checkbox" name = "otdelka[fruits]" id="otdelka__frukt__bisquit" class="type_cake" value="Фрукты">
                                        <label for="otdelka__frukt__bisquit">Фрукты</label>
                                    </div>
                                </div>
                            </div>

                            <!-- ДЕКОР -->
                            <div class="constructor_item" data-decor>
                                <h5 class="item_title">Декоративные элементы</h5>
                                <div class="item_content d-flex">
                                    <div class="item_label">
                                        <input type="checkbox" name="decor[struzhka]" id="dekor__struzhka__bisquit" class="type_cake" value="Шоколадная стружка" checked>
                                        <label for="dekor__struzhka__bisquit">Шоколадная стружка</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="checkbox" name="decor[nuts]" id="dekor__nuts__bisquit" class="type_cake" value="Орехи">
                                        <label for="dekor__nuts__bisquit">Орехи</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="checkbox" name="decor[marshmellow]" id="dekor__marshmellow__bisquit" class="type_cake" value="Маршмеллоу">
                                        <label for="dekor__marshmellow__bisquit">Маршмеллоу</label>
                                    </div>
                                    <div class="item_label">
                                        <input type="checkbox" name="decor[beze]" id="dekor__beze__bisquit" class="type_cake" value="Безе">
                                        <label for="dekor__beze__bisquit">Безе</label>
                                    </div>
                                </div>
                            </div>

                            <!-- НАДПИСЬ -->
                            <div class="constructor_item" data-text>
                                <h5 class="item_title">Надпись на торте</h5>
                                <input type="text" name="nadpis" class="constructor_input_user" placeholder="Ваша надпись">
                            </div>

                            <!-- ПОЖЕЛАНИЯ -->
                            <div class="constructor_item" data-wish>
                                <h5 class="item_title">Ваши пожелания</h5>
                                <textarea name="pozhelanie" class="constructor_input_user" cols="10" rows="3"></textarea>
                            </div>

                        </div>

                    </div>

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 d-flex align-items-strart justify-content-center">

                        <button type="button" class="constructor_button_offer" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            Оформить заказ
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Оформление заказа</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <!-- контент модалки -->
                                        <div class="constructor_wrapper border d-flex justify-content-between flex-column" data-right_form>

                                            <!-- ВЕС -->
                                            <div class="constructor_item item_after" data-weight>
                                                <h5 class="item_title text-center">Вес</h5>
                                                <p class="modal_descr text-center">Расчет торта идёт из простой формулы: 1кг на 5 персон</p>
                                                <div class="item_content d-flex justify-content-center">
                                                    <div class="item_label">
                                                        <input type="radio" name="weight"  id="ves_1" class="type_cake" value="1кг" checked="">
                                                        <label for="ves_1">1 кг</label>
                                                    </div>
                                                    <div class="item_label">
                                                        <input type="radio" name="weight" id="ves_2" class="type_cake" value="2кг">
                                                        <label for="ves_2">2 кг</label>
                                                    </div>
                                                    <div class="item_label">
                                                        <input type="radio" name="weight" id="ves_3" class="type_cake" value="3кг">
                                                        <label for="ves_3">3 кг</label>
                                                    </div>
                                                    <div class="item_label">
                                                        <input type="radio" name="weight" id="ves_4" class="type_cake" value="4кг">
                                                        <label for="ves_4">4 кг</label>
                                                    </div>
                                                    <div class="item_label">
                                                        <input type="radio" name="weight" id="ves_5" class="type_cake" value="5кг">
                                                        <label for="ves_5">5 кг</label>
                                                    </div>
                                                    <div class="item_label">
                                                        <input type="radio" name="weight" id="ves_6" class="type_cake" value="6кг">
                                                        <label for="ves_6">6 кг</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class=" text-center">Предварительная сумма составит: <span> 5000 </span> &#8381;</p>

                                            <!-- ВЕС ДЛЯ БЕНТО ТОРТА -->
                                            <div class="constructor_item item_after" data-bento_weight>
                                                <h5 class="item_title text-center">Вес</h5>
                                                <p class="modal_descr text-center">Бенто тортик рассчитан на одного-двух человек</p>
                                                <div class="item_content d-flex justify-content-center">
                                                    <div class="item_label">
                                                        <input type="radio" name="weight"  id="ves_bento_1" class="type_cake" value="0,3кг" checked>
                                                        <label for="ves_bento_1">300г</label>
                                                    </div>
                                                    <div class="item_label">
                                                        <input type="radio" name="weight" id="ves_bento_2" class="type_cake" value="0,6кг">
                                                        <label for="ves_bento_2">600г</label>
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- ИМЯ ТЕЛЕФОН ДАТА -->
                                            <div class="constructor_item item_after">
                                                <h5 class="item_title text-center">Для кого</h5>
                                                <div class="item_content d-flex">

                                                    <p class="basket_modal_descr">Укажите свои данные и мы свяжемся для подтверждения</p>
                                                    <input type="text" name="userName" id="userName" placeholder="имя" required>

                                                    <input type="text" name="userPhone"  id="userPhone" placeholder="телефон" required>

                                                </div>
                                            </div>

                                            <div class="constructor_item  item_after">
                                                <h5 class="item_title text-center">Дата</h5>
                                                <div class="item_label">
                                                    <p class="basket_modal_descr">К какому дню все должно быть готово?</p>
                                                    <input type="date" name="userData"  id="userData" placeholder="дата" required>
                                                </div>
                                            </div>

                                            <div class="constructor_item  item_after">
                                                <div class="item_label d-flex justify-content-center align-items-center">
                                                    <input type="checkbox"  id="basket_privacy_check" required checked>
                                                    <p class="privacy_descr">Я согласен(согласна) на обработку персональных данных</p>
                                                </div>
                                                <p><a href="<?=$this->alias('privacy')?>" class="privacy_basket">Политика конфиденциальности</a></p>
                                            </div>

                                        </div>
                                        <!-- контент модалки -->

                                    </div>
                                    <div class="modal-footer justify-content-center">

                                        <button type="submit" class="btn constructor_button_offer">Отправить</button>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                    <!-- row -->
                </div>
                <!-- form -->
            </form>
            <!-- container -->
        </div>
    </section>

</main>
