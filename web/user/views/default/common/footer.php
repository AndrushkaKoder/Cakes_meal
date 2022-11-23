<?php
$display = $this->getController() === 'login' ? 'none' : 'block';
?>
<footer class="footer" id="footer" style="display: <?=$display?>">
    <div class="container">

        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12 d-flex justify-content-center align-items-center">
                <a href="<?=$this->alias()?>" class="cakes_logo cakes_logo_footer">Cakes Meal</a>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 d-flex justify-content-evenly align-items-center">
                <a href="https://vk.com" target="_blank"><i class="fa-brands fa-vk"></i></a>
                <a href="https://www.instagram.com" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://web.whatsapp.com/" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="https://web.telegram.org/" target="_blank"><i class="fa-brands fa-telegram"></i></a>
            </div>

            <div class="col-lg-4 col-md-12 col-sm-12 d-flex justify-content-center align-items-center">
                <span class="footer_span"> Наш телефон:</span>
                <?php if(!empty($contacts)):?>
                    <?php foreach ($contacts as $item):?>
                        <a href="tel:<?=$item['phone']?>" class="footer_phone">+7<?=$item['phone']?></a>
                    <?php endforeach;?>
                <?php endif;?>

            </div>

        </div>

        <div class="row">
            <div class="col-lg-12 d-flex justify-content-center align-items-center">
                <a href="<?=$this->alias('privacy')?>" class="confidential">
                    Политика конфиденциальности
                </a>
            </div>
        </div>

    </div>
</footer>





<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script src="https://kit.fontawesome.com/bf8cd5452d.js" crossorigin="anonymous"></script>
<script src="https://api-maps.yandex.ru/2.1/?apikey=30953be2-3a85-4711-b1fd-67ec9ae73bf9&lang=ru_RU"></script>
<?php $this->getScripts()?>
<script>
    new WOW().init();
</script>
</body>
</html>