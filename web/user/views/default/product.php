<!--КАРТОЧКА ТОВАРА-->
<main>
    <div class="bread">
        <div class="container">
            <div class="row p-2">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <?php if(!empty($product)):?>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= $this->alias('catalog')?>">В каталог</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?=$product['name']?></li>
                        </ol>
                        <?php endif;?>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <section class="product">
        <div class="container">
            <div class="row">

                <?php if(!empty($product)):?>

                 <div class="col-xl-6 col-md-6 col-sm-12">
                            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php if(!empty($product['gallery_img']) || !empty($product['img'])):?>
                                        <?php if(!empty($product['img'])):?>
                                            <div class="carousel-item active" style="background: black; height: 100%">
                                                <img src="<?=$this->img($product['img'])?>" class="d-block w-100" alt="...">
                                            </div>
                                        <?php endif;?>

                                        <?php if(!empty($product['gallery_img'])):?>
                                            <?php foreach (json_decode($product['gallery_img'], true) as $key => $item):?>
                                                <div class="carousel-item <?=!$key && !$product['img'] ? 'active' : ''?>" style="background: black; height: 100%">
                                                    <img src="<?=$this->img($item)?>" class="d-block w-100" alt="...">
                                                </div>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                    <?php endif;?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6 col-sm-12 d-flex flex-column justify-content-start">
                            <form action="" method="GET">
                            <h3 class="product_title text-center"><?=$product['name']?></h3>

                            <div class="product_price_wrapper d-flex justify-content-evenly align-items-center">
                                <p class="product_price">
                                    <?php if(!empty($product['old_price'])):?>
                                        <span class="assortment_price_old" style="font-size: 17px"><?=$product['old_price']?> &#8381</span>
                                    <?php endif;?>

                                    <span><?=$product['price']?> </span> &#8381;
                                </p>

                               <button type="submit" class="text-center product_button_buy" <?=$this->setAddToCart($product)?>>Купить</button>
                            </div>


                            <p class="product_description">
                               <?=$product['content']?>
                            </p>
                            <hr style="background-color: black; height: 2px">
                            <?php if(!empty($product['filters'])):?>
                                <div class="product_filters text-center">
                                    <?php foreach ($product['filters'] as $item):?>
                                        <p>
                                            <?=$item['name']?>: <?= implode(',' , array_column($item['values'], 'name'))?>
                                        </p>
                                    <?php endforeach;?>

                                </div>
                            <?php endif;?>


                            </form>
                        </div>
                <?php endif;?>

            </div>
        </div>



    </section>


</main>
