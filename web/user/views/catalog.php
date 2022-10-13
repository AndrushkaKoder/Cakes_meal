<!--ФИЛЬТР ТОВАРОВ-->
<main>
    <div class="bread">
        <div class="container">
            <div class="row p-5">
                <div class="col-12 p-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=\App::PATH()?>">На главную</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ассортимент</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- ОТКРЫВАШКА ФИЛЬТРА -->
    <button type="button" class=" filter_button_btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <i class="fa-sharp fa-solid fa-sort"></i>
    </button>

    <!-- ФИЛЬТР -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog filter_dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ищите нашу выпечку удобнее</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!--  тело модалки  -->
                <div class="modal-body">
                    <form action="" method="GET">
                        <div class="container">
                            <div class="row">
                                <div class="filter_modal">
                                    <div class="filter_modal_content d-flex justify-content-center flex-column p-5">
                                        <!--   range slider  -->
                                        <h4 class="text-center">Цена</h4>
                                        <div id="slider" class="m-auto" style="width: 50%; margin: 20px 0"></div>
                                        <!--   range slider  -->
                                        <div class="slider_values d-flex">
                                            <input type="num" id="min_value" placeholder="от 500 &#8381">
                                            <input type="num" id="max_value" placeholder="до 10000 &#8381">
                                        </div>
                                    </div>
                                    <h4 class="text-center">Тип изделия</h4>
                                    <div class="filter_modal_content d-flex flex-wrap justify-content-center">

                                        <input type="checkbox" class="filter_type" name="filter_type_bisquit" id="filter_type_bisquit">
                                        <label for="filter_type_bisquit">Бисквитные торты</label>
                                        <input type="checkbox" class="filter_type" name="filter_type_muss" id="filter_type_muss">
                                        <label for="filter_type_muss">Муссовые торты</label>
                                        <input type="checkbox" class="filter_type" name="filter_type_bisquit" id="filter_type_bento">
                                        <label for="filter_type_bento">Бенто торты</label>
                                        <input type="checkbox" class="filter_type" name="filter_type_cupcake" id="filter_type_cupcake">
                                        <label for="filter_type_cupcake">Пирожные</label>
                                        <input type="checkbox" class="filter_type" name="filter_type_trifle" id="filter_type_trifle">
                                        <label for="filter_type_trifle">Пирожные в стаканчике</label>

                                    </div>
                                    <hr>
                                    <h4 class="text-center">Начинка</h4>
                                    <div class="filter_modal_content d-flex flex-wrap justify-content-center">

                                         <input type="checkbox" class="filter_type" name="filter_params_fruiit" id="filter_params_fruiit">
                                     <label for="filter_params_fruiit">Фруктовые</label>
                                         <input type="checkbox" class="filter_type" name="filter_params_choko" id="filter_params_choko">
                                    <label for="filter_params_choko">Шоколадные</label>
                                        <input type="checkbox" class="filter_type" name="filter_params_cheese" id="filter_params_cheese">
                                    <label for="filter_params_cheese">Творожные</label>
                                        <input type="checkbox" class="filter_type" name="filter_params_berrys" id="filter_params_berrys">
                                        <label for="filter_params_berrys">Ягодные</label>
                                    </div>

                                    <hr>
                                    <div class="filter_modal_content d-flex justify-content-center">
                                        <button type="submit" class="btn constructor_button_offer m-auto" style="width: 30%">Поиск</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--  тело модалки  -->


            </div>
        </div>
    </div>
<!--ФИЛЬТР ТОВАРОВ-->


<!--  ассортимент  -->
    <section class="assortment">
        <div class="container">


            <?php if(!empty($data)):?>
                <?php foreach ($data as $item):?>
                    <?php if(!empty($item['join']['goods'])):?>

                    <div class="row overflow-hidden">
                        <div class="col-12 text-center wow bounceInRight">
                            <h2><?=$item['name']?></h2>
                        </div>
                    </div>


                    <div class="row r_">

                        <?php foreach ($item['join']['goods'] as $value):?>
                            <div href="#" class="col-lg-4 col-md-6 col-sm-12 wow bounceInUp" data-wow-duration="1s">
                                <a href="<?=$this->alias('product')?>" class="assortment_item" style="display: block; cursor: pointer; width: 90%; color: black">
                                    <div class="assortment_image">
                                        <img src="<?=$this->img($value['img'])?>" alt="изображение">
                                    </div>
                                    <div class="assortment_title">
                                        <?=$value['name']?>
                                    </div>
                                    <div class="assortment_descr">
                                        <?=$value['short_content']?>
                                    </div>
                                    <div class="assortment_price">
                                        <span class="assortment_price_num"><?=$value['price'] ?? 0?></span>
                                        <span>&#8381;/кг</span>
                                    </div>
                                    <div class="assortment_btn">
                                        <button class="assortment_btn_button">Купить</button>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach;?>

                    </div>
                    <?php endif;?>
                <?php endforeach;?>
            <?php endif;?>
        </div>
    </section>


</main>

