<<<<<<< HEAD:web/user/views/catalog.php
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
    <?php if(!empty($catalogFilters) || !empty($catalogPrices)):?>
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
                                        <?php if(!empty($catalogPrices)):?>
                                            <div class="filter_modal_content d-flex justify-content-center flex-column p-5">
                                                <!--   range slider  -->
                                                <h4 class="text-center">Цена</h4>
                                                <div id="slider" class="m-auto" style="width: 50%; margin: 20px 0"></div>
                                                <!--   range slider  -->
                                                <div class="slider_values d-flex">
                                                    <input type="text" id="min_value" data-min_price = "<?=$catalogPrices['min_price']?>">
                                                    <input type="text" id="max_value" data-max_price = "<?=$catalogPrices['max_price']?>">
                                                </div>
                                            </div>

                                            <script>
                                                let minValuePrice = <?=$catalogPrices['min_price']?>;
                                                let maxValuePrice = <?=$catalogPrices['max_price']?>;
                                            </script>
                                        <?php endif;?>

                                        <?php if(!empty($catalogFilters)):?>
                                            <?php foreach ($catalogFilters as $item):?>
                                                <h4 class="text-center"><?=$item['name']?></h4>
                                                <div class="filter_modal_content d-flex flex-wrap justify-content-center">

                                                     <?php foreach ($item['values'] as $value):?>
                                                         <input type="checkbox" value="<?=$value['id']?>" class="filter_type" name="filters[]" id="filter_<?=$value['id']?>">
                                                         <label for="filter_<?=$value['id']?>"><?=$value['name']?></label>
                                                    <?php endforeach;?>


                                                </div>
                                                <hr>
                                            <?php endforeach;?>
                                        <?php endif;?>

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
    <?php endif;?>
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
                                <a href="<?=$this->alias(['product'=> $value['alias']])?>" class="assortment_item" style="display: block; cursor: pointer; width: 90%; color: black">
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
                                        <span class="assortment_price_num"><?=$value['price'] ?? 0?> <span> &#8381;/шт</span></span>
                                        <span class="assortment_price_old" style="font-size: 18px">1650 <span style="font-size: 16px"> &#8381;/шт</span></span>

                                    </div>

<!--                                    <div class="assortment_price_old">-->
<!--                                        -->
<!--                                        <span>&#8381;/кг</span>-->
<!--                                    </div>-->
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

=======
<!--ФИЛЬТР ТОВАРОВ-->
<main>
    <div class="bread">
        <div class="container">
            <div class="row p-5">
                <div class="col-12 p-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=$this->alias()?>">На главную</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ассортимент</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- ОТКРЫВАШКА ФИЛЬТРА -->
    <?php if(!empty($catalogFilters) || !empty($catalogPrices)):?>
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
                                        <?php if(!empty($catalogPrices)):?>
                                            <div class="filter_modal_content d-flex justify-content-center flex-column p-5">
                                                <!--   range slider  -->
                                                <h4 class="text-center">Цена</h4>
                                                <div id="slider" class="m-auto" style="width: 50%; margin: 20px 0"></div>
                                                <!--   range slider  -->
                                                <div class="slider_values d-flex">
                                                    <input type="text" id="min_value" data-min_price = "<?=$catalogPrices['min_price']?>">
                                                    <input type="text" id="max_value" data-max_price = "<?=$catalogPrices['max_price']?>">
                                                </div>
                                            </div>

                                            <script>
                                                let minValuePrice = <?=$catalogPrices['min_price']?>;
                                                let maxValuePrice = <?=$catalogPrices['max_price']?>;
                                            </script>
                                        <?php endif;?>

                                        <?php if(!empty($catalogFilters)):?>
                                            <?php foreach ($catalogFilters as $item):?>
                                                <h4 class="text-center"><?=$item['name']?></h4>
                                                <div class="filter_modal_content d-flex flex-wrap justify-content-center">

                                                     <?php foreach ($item['values'] as $value):?>
                                                         <input type="checkbox" value="<?=$value['id']?>" class="filter_type" name="filters[]" id="filter_<?=$value['id']?>">
                                                         <label for="filter_<?=$value['id']?>"><?=$value['name']?></label>
                                                    <?php endforeach;?>


                                                </div>
                                                <hr>
                                            <?php endforeach;?>
                                        <?php endif;?>

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
    <?php endif;?>
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
                                <a href="<?=$this->alias(['product'=> $value['alias']])?>" class="assortment_item" style="display: block; cursor: pointer; width: 90%; color: black">
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
                                        <span class="assortment_price_old" style="font-size: 18px">1650 <span style="font-size: 16px"> &#8381;/шт</span></span>
                                        <span class="assortment_price_num"><?=$value['price'] ?? 0?> <span> &#8381;/шт</span></span>

                                    </div>

<!--                                    <div class="assortment_price_old">-->
<!--                                        -->
<!--                                        <span>&#8381;/кг</span>-->
<!--                                    </div>-->
                                    <div class="assortment_btn">
                                        <button class="assortment_btn_button" <?=$this->setAddToCart($value)?>>Купить</button>
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

>>>>>>> 2e2162608b52d77abe9c5daf01b432e99b9bf943:web/user/views/default/catalog.php
