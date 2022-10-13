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

                <?php if(!empty($goods)):?>
                <?php foreach ($goods as $item):?>
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

                        <div class="col-xl-6 col-md-6 col-sm-12 d-flex flex-column justify-content-between">

                            <h3 class="product_title text-center"><?=$item['name']?></h3>

                            <p class="product_description">
                               <?=$item['short_content']?>
                            </p>
                            <p class="product_price"><span><?=$item['price']?> </span> &#8381;</p>
                            <p class="text-center"><button class="about_btn m-auto text-center">Купить</button></p>

                        </div>
                <?php endforeach;?>
                <?php endif;?>

            </div>
        </div>



    </section>


</main>
