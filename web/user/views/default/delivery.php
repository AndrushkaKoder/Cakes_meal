<main>

    <div class="bread">
        <div class="container">
            <div class="row p-2">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=$this->alias()?>">На главную</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Доставка</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="delivery">

      <div class="container">
          <div class="row">
              <div class="col-12 text-center">
                  <h2 class="mb-4">Доставка и Оплата</h2>
                  <p class="delivery_subtitle">Оформите <a href="<?=$this->alias('catalog')?>">заказ</a> на нашем сайте или по телефону:
                      <a href="tel:9623734441">+7-962-373-44-41</a> </p>
              </div>
          </div>

          <div class="row mt-5">
              <div class="col-xl-6 col-md-12 col-sm-12">
                  <div class="lk_wrapper_data p-3">
                    <h4 class="text-center">Доставка осуществляется по городу Калуга</h4>
                        <?php if(!empty($information)):?>
                      <ul class="delivery_description">
                          <?php foreach ($information as $item):?>
                          <li>Минимальная сумма заказа на доставку - <span class="delivery_info"><?=$item['min_price_delivery']?></span> &#8381;</li>
                          <li>Минимальная сумма заказа на самовывоз - <span class="delivery_info"><?=$item['min_price_export']?></span> &#8381;</li>
                          <li>Заказы принимаются с <span class="delivery_info"><?=$item['work_start']?></span> до <span class="delivery_info"><?=$item['work_end']?></span></li>
                          <li>Заказы доставляются на следующий день после 12:00, либо к указанной дате</li>
                          <li>При заказе от <span class="delivery_info"><?=$item['gift_price']?></span> &#8381; плитка шоколада - в подарок</li>
                            <?php endforeach;?>
                      </ul>

                      <?php endif;?>

                      <p class="delivery_address">Доставка осуществляется по Московскому, Октябрьскому и Ленинскому округам города Калуга</p>
                  </div>
              </div>

              <div class="col-xl-6 col-md-12 col-sm-12">
                  <div class="lk_wrapper_orders">

                      <div id="YMapsID" style="width: 100%; height: 100%;"></div>

                  </div>
              </div>

          </div>


      </div>

    </section>


</main>
