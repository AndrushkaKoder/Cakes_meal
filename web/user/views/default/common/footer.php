<footer class="footer" id="footer">
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
                <span class="footer_span"> наш телефон:</span>
                <a href="tel:89999999999" class="footer_phone">+7 999 999 99 99</a>
                <div class="email">
                </div>
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



</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script src="https://kit.fontawesome.com/bf8cd5452d.js" crossorigin="anonymous"></script>
<?php $this->getScripts()?>
<script>
    new WOW().init();
</script>
</body>
</html>