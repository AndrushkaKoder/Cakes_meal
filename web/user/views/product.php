<!--КАРТОЧКА ТОВАРА-->
<main>
    <div class="bread">
        <div class="container">
            <div class="row p-5">
                <div class="col-12 p-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= $this->alias()?>">На главную</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Продукт</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <section class="product">
        <div class="container">
            <div class="row">

                <?php if(!empty($product)):?>
                <?php foreach ($product as $item):?>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active" style="background: black; height: 100%">
                                        <img src="./images/bisquit/bisquit1.jpg" class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item" style="background: red; height: 100%">
                                        <img src="../views/images/bisquit/bisquit1.jpg" class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item" style="background: green; height: 100%">
                                        <img src="../views/images/bisquit/bisquit3.jpg.jpg" class="d-block w-100" alt="...">
                                    </div>
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

                            <h3 class="product_title text-center"><?=$item['name']?></h3>

                            <div class="product_price_wrapper d-flex justify-content-evenly align-items-center">
                                <p class="product_price"><span><?=$item['price']?> </span> &#8381;</p>
                               <button type="submit" class="m-auto text-center product_button_buy">Купить</button>
                            </div>


                            <p class="product_description">
                               <?=$item['long_content']?>
                            </p>
                            <hr style="background-color: black; height: 2px">
                            <div class="product_filters text-center">
                                Орехи, шоколад, мастика
                            </div>


                        </div>
                <?php endforeach;?>
                <?php endif;?>

            </div>
        </div>



    </section>


</main>
