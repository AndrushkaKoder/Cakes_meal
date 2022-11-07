<main>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <a href="<?=$this->alias('')?>"><p style="color: black"><i class="fa-solid fa-arrow-left"></i> Я передумал</p></a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h3>Регистрация</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12 m-auto">
                    <form action="#" method="post">
                        <div class="mb-4">
                            <p class="login_description text-center">Имя</p>
                            <input type="text" class="form-control" name="name" id="lk_name" placeholder="Имя" required value="<?=$_POST['name']?>">
                        </div>
                        <div class="mb-4">
                            <p class="login_description text-center">Фамилия</p>
                            <input type="text" class="form-control" name="surname" id="lk_surname" placeholder="Фамилия" required value="<?=$_POST['surname']?>">
                        </div>
                        <div class="mb-4">
                            <p class="login_description text-center">Дата рождения</p>
                            <input type="date" class="form-control" name="birthday" id="birthday" placeholder="Дата рождения" required value="<?=$_POST['birthday']?>">
                        </div>
                        <div class="mb-4">
                            <p class="login_description text-center">Номер телефона</p>
                            <input type="number" class="form-control" name="phone" id="lk_phoneNumber" placeholder="Телефон" required value="<?=$_POST['phone']?>">
                        </div>
                        <div class="mb-4">
                            <p class="login_description text-center">Электронная почта</p>
                            <input type="email" class="form-control" name="email" id="lk_email" placeholder="Почта" value="<?=$_POST['email']?>">
                        </div>
                        <div class="mb-4">
                            <p class="login_description text-center">Пароль</p>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Пароль">
                        </div>
                        <div class="mb-4">
                            <p class="login_description text-center">Повторите пароль</p>
                            <input type="password" class="form-control" name="password_repeat" id="password_repeat" placeholder="Повторите пароль">
                            <?php if(!empty($message)):?>
                                <div class="alert alert-danger m-3 text-center" role="alert">
                                    <?=$message?>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="mb-4 text-center">
                            <input type="checkbox" name="basket_privacy_check" id="basket_privacy_check" required checked>
                            <p class="privacy_descr">Я согласен(согласна) на обработку <a href="<?=$this->alias('privacy')?>" class="privacy_basket"> персональных данных</a></p>

                        </div>
                        <button type="submit" class="assortment_button">Регистрация</button>
                    </form>


                </div>
            </div>
        </div>
    </section>
</main>

<!--<form action="#" method="post" class="lk_form">-->
<!--    <input type="text" name="name" id="lk_name" placeholder="Ваше имя" value="Аркадий">-->
<!--    <input type="text" name="surname" id="lk_surname" placeholder="Ваша фамилия" value="Залупкин">-->
<!--    <input type="number" name="phoneNumber" id="lk_phoneNumber" placeholder="Контактный номер" value="894235325243">-->
<!--    <input type="email" name="email" id="lk_email" placeholder="Электронная почта" value="sfsdf@yandex.ru">-->
<!--    <input type="text" name="dirthday" id="dirthday" placeholder="Дата рождения" value="01.01.2000">-->
<!--    <input type="password" name="password" id="password" placeholder="Введите новый пароль">-->
<!--    <input type="password" name="password" id="password" placeholder="Повторите пароль">-->
<!--    <button type="button" class="assortment_button">Сохранить</button>-->
<!--</form>-->