<!-- СВАЙПЕР -->
<?php if(!empty($sales)):?>
    <section class="header_swiper">

        <div class="swiper">
            <div class="swiper-wrapper">
                <!-- Slides -->
                <?php foreach ($sales as $item):?>
                    <div class="swiper-slide" style="background:url('<?=$this->img($item['img'])?>'); background-size: cover; background-position: top">
                        <div class="slide_content" data-wow-duration="1s">
                            <h3><?=$item['name']?></h3>
                            <p><?=$item['short_content']?></p>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>

<!--            <div class="swiper-pagination">-->
                <!-- <i class="fa-solid fa-cupcake swiper_pagination_img" style="font-size:20px;"></i>
                <i class="fa-solid fa-cupcake swiper_pagination_img"></i>
                <i class="fa-solid fa-cupcake swiper_pagination_img"></i> -->
<!--            </div>-->
<!--                <div class="swiper-button-prev"></div>-->
<!--                <div class="swiper-button-next"></div>-->

            <!-- <div class="swiper-scrollbar"></div> -->
        </div>

    </section>
<?php endif;?>




<main>
    <!-- ПРЕИМУЩЕСТВА -->
    <?php if(!empty($tizzers)):?>
        <section class="r_ advantages" style="background: white">
            <div class="container">

                <div class="row overflow-hidden">
                    <div class="col-12 wow bounceInDown" data-wow-duration="1.5s">
                        <h2 class="advantages_title title text-center">Выбирая Cakes Meal - вы выбираете:</h2>
                    </div>
                </div>


                <div class="row justify-content-evenly">

                    <?php foreach ($tizzers as $item):?>
                        <div class="col-lg-4 d-flex align-items-center flex-column text-center  wow bounceInLeft" data-wow-duration="1s"  data-wow-delay="1s">
                            <img src="<?=$this->img($item['img'])?>" alt="" width="85px">
                            <h2><?=$item['name']?></h2>
                            <p><?=$item['short_content']?></p>
                        </div>
                    <?php endforeach;?>

                </div>

            </div>
        </section>
    <?php endif;?>


    <!-- АССОРТИМЕНТ -->
    <?php if(!empty($assortment)):?>
        <section class="r_ assortment">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="assort_title title text-center">Наш ассортимент</h2>
                    </div>
                </div>

                <?php foreach ($assortment as $item):?>

                    <div class="row featurette wow bounceInUp <?=$item['id'] % 2 === 0 ? 'flex-row-reverse' : ''?>" data-wow-duration="2s" data-wow-delay="">
                        <div class="col-xl-7 col-md-6 col-sm-12 revert text-center">
                            <h2 class="featurette-heading title_adv"><?=$item['name']?></span></h2>
                            <p class="lead"><?=$item['short_content']?></p>
                            <p><a href="<?=$this->alias(['catalog'=>$item['alias']])?>" class="assortment_button" data-wow-duration="1s">подробнее</a></p>
                        </div>
                        <div class="col-xl-5 col-md-6 col-sm-12 d-flex justify-content-center">
                            <div class="assortment_img_container">
                                <img src="<?=$this->img($item['img'])?>" alt="bisquit" class="assortment_img">
                            </div>

                        </div>
                    </div>
                    <hr class="featurette-divider">
                <?php endforeach;?>

            </div>
        </section>
    <?php endif;?>


    <!-- ССЫЛКА НА КОНСТРУКТОР -->
    <?php if(!empty($backgroundImage)):?>
    <?php foreach ($backgroundImage as $item):?>
        <section class="r_ constructor" style="background: url('<?=$this->img($item['name'])?>') fixed 50% no-repeat">
    <?php endforeach;?>
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2 class="constructor_title">Не нашли подходящего торта?</h2>
                        <p class="constructor_descr">загляните в наш Конструктор тортов!</p>
                        <p><a href="<?=$this->alias('constructor')?>" class="assortment_button constructor_btn">в конструктор</a></p>
                    </div>
                </div>
            </div>

        </section>
    <?php endif;?>


    <!-- ЧАСТЫЕ ВОПРОСЫ -->
    <?php if(!empty($questions)):?>
        <section class="questions">

            <div class="container">
                <div class="questions_wrapper  wow bounceInUp" data-wow-duration="2s">

                    <div class="questions_title text-center">
                        <h2>Часто задаваемые вопросы</h2>
                    </div>

                    <div class="questions_content">

                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <?php foreach ($questions as $item):?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-heading<?=$item['num']?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?=$item['num']?>" aria-expanded="false" aria-controls="flush-collapse<?=$item['num']?>">
                                            <?=$item['name']?>
                                        </button>
                                    </h2>
                                    <div id="flush-collapse<?=$item['num']?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?=$item['num']?>" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body"><?=$item['answer']?></div>
                                    </div>
                                </div>
                            <?php endforeach;?>

                        </div>

                    </div>
                </div>
            </div>

        </section>
    <?php endif;?>


    <!-- ОСТАЛИСЬ ВОПРОСЫ -->
    <section class="r_ callback">

        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="callback_title">Остались вопросы?</h2>
                    <p class="callback_descr">Оставьте свой телефон мы свяжемся с Вами</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">

                    <form action="#" method="post" class="callback_form">
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Ваше имя" required>

                        </div>
                        <div class="mb-3">
                            <input type="text" name="phone" class="form-control" id="phone" placeholder="Телефон" required>
                        </div>
                        <p class="personal_data_p text-center">Вы соглашаетесь на с нашими условиями по <a href="<?=$this->alias('privacy')?>" class="personal_data">обработке персональных данных</a></p>

                        <button type="submit" class="assortment_button callback_button">Отправить</button>
                    </form>

                    <img src="<?=$this->getTemplateImg()?>bg/choko1.png" alt="#" class="choco_img wow bounceInLeft">

                </div>
            </div>
        </div>

    </section>

<!--    <div class="arrowUp">-->
<!--        <i class="fa-solid fa-arrow-up"></i>-->
<!--    </div>-->

</main>
