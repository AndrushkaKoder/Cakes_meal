<main>
<!--    КОРЗИНА   -->
    <div class="bread">
        <div class="container">
            <div class="row p-5">
                <div class="col-12 p-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= $this->alias()?>">На главную</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Корзина</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="basket">

        <form action="" method="GET" class="basketForm">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12">
                        <div class="basket_product">
                            <div class="basket_product_name">
                                Торт Прага
                            </div>
                        <div class="basket_counter">
                            <button class="basket_counter_button minus">-</button>
                            <input class="basket_counter_input" value="1">
                            <button class="basket_counter_button plus">+</button>
                        </div>
                            <div class="basket_product_price">
                                100500 &#8381;
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12">
                        <div class="basket_settings">
                            <div class="basket_total">
                               <p>Итого</p>
                                <p>100500</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </section>
</main>