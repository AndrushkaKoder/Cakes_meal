<main>
    <div class="bread">
        <div class="container">
            <div class="row p-5">
                <div class="col-12 p-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=\App::PATH()?>">На главную</a></li>
                            <li class="breadcrumb-item active" aria-current="page">О нас</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>



    <!-- СЕКЦИЯ С ТЕКСТОМ -->
    <section class="about_section" style="background:url('<?=$this->getTemplateImg()?>bg/homemade-bakery-recipes-concept-LQJ9YC7.jpg') fixed 50% no-repeat">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2>Расскажем Вам о Нас</h2>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 m-auto">
                    <p class="about_content">Наша студия в городе Калуга производит торты и пирожные, которые обладают изысканным нежным вкусом,
                        характерным для выпечки премиум класса ручной работы. Основа нашей философии - быть лучшими, представляя традиционные сладости, приготовленные
                        исключительно по оригинальным рецептам и безупречному внешнему виду.
                    </p>
                    <p class="about_content">Мы используем только высококачественное сырье, применяем современные
                        технологии в области производства продукции.
                    </p>
                    <p class="about_content">Отдельно мы концентрируемся на контроле качества готовой продукции и стремимся к
                        достижению оптимального сочетания цена — качество.
                    </p>
                    <p class="about_content">Доставка нашей продукции осуществляется ежедневно, без выходных.
                    </p>
                </div>
            </div>
        </div>

    </section>

    <!-- СЕКЦИЯ С ФОТО -->
    <section class="about_master">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <div class="about_master_img">
                        <img src="<?=$this->getTemplateImg()?>/elena2.png" alt="master">
                    </div>

                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 text-center m-auto p-5">
                    <h3 class="about_master_name">Елена</h3>
                    <p class="about_content text-center">Шеф кондитер</p>
                    <p class="about_content text-center">Мы приготовим заказной торт для вашего торжества. Делаем торты на заказ любой сложности и размера.
                        Чем больше, тем вкуснее. Смотрите какие они у нас получаются классные!
                    </p>
                </div>
                <div class="col-12">
                    <p><a href="<?=$this->alias('catalog')?>" class="about_btn m-auto">в каталог</a></p>
                </div>
            </div>
        </div>
    </section>


    <section class="about_photos d-flex flex-wrap">
        <div class="about_photos_item">
            <img src="<?=$this->getTemplateImg()?>/bento/bento.jpg" alt="#" class="about_photos_img">
        </div>
        <div class="about_photos_item">
            <img src="<?=$this->getTemplateImg()?>/bento/bento-hover.jpg" alt="#" class="about_photos_img">
        </div>
        <div class="about_photos_item">
            <img src="<?=$this->getTemplateImg()?>/bisquit/bisq.jpg" alt="#" class="about_photos_img">
        </div>
        <div class="about_photos_item">
            <img src="<?=$this->getTemplateImg()?>/bisquit/bisquit_hover.jpg" alt="#" class="about_photos_img">
        </div>
        <div class="about_photos_item">
            <img src="<?=$this->getTemplateImg()?>/muss/muss.jpg" alt="#" class="about_photos_img">
        </div>
        <div class="about_photos_item">
            <img src="<?=$this->getTemplateImg()?>/muss/muss-hover.jpg" alt="#" class="about_photos_img">
        </div>
        <div class="about_photos_item">
            <img src="<?=$this->getTemplateImg()?>/merengy/merenga1.jpg" alt="#" class="about_photos_img">
        </div>
        <div class="about_photos_item">
            <img src="<?=$this->getTemplateImg()?>/trifles/trifle-hover.jpg" alt="#" class="about_photos_img">
        </div>
    </section>


</main>
