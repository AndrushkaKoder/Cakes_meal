<main>

    <div class="bread">
        <div class="container">
            <div class="row p-2">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=$this->alias()?>">На главную</a></li>
                            <li class="breadcrumb-item active" aria-current="page">По Вашему запросу</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div href="#" class="col-lg-4 col-md-6 col-sm-12 wow bounceInUp" data-wow-duration="1s">
                <a href="#" class="assortment_item" style="display: block; cursor: pointer; width: 90%; color: black">
                    <div class="assortment_image">
                        <img src="" alt="изображение">
                    </div>
                    <div class="assortment_title">
                       name
                    </div>
                    <div class="assortment_descr">
                        short_content
                    </div>
                    <!-- старая новая цена -->
                    <div class="assortment_price">
                        <?php if(!empty($value['old_price'])):?>
                            <span class="assortment_price_old" style="font-size: 18px"><?=$value['old_price']?> <span style="font-size: 16px"> &#8381;/шт</span></span>
                        <?php endif;?>

                        <span class="assortment_price_num"><?=$value['price'] ?? 0?> <span> &#8381;/шт</span></span>

                    </div>

                    <!-- кнопка Купить -->
                    <div class="assortment_btn">
                        <button class="assortment_btn_button" <?=$this->setAddToCart($value)?>>Купить</button>
                    </div>
                </a>
            </div>
        </div>
    </div>




</main>
