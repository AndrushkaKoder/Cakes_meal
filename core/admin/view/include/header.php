<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta type="keywords" content="...">
    <meta type="description" content="...">
    <title>Document</title>

    <?php $this->getStyles()?>

</head>
<body>
    <div class="wq-page">

        <aside class="wq-aside-menu">
            <div class="wq-aside-menu__wrap">
                <div class="wq-aside-menu__button">
                    <button type="button" class="wq-icon-menu _btn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
                <?php if($this->menu):?>

                    <div class="wq-aside-menu__search wq-search-aside">
                        <form action="#" class="wq-search-aside__form wq-form-select" data-menu-search-form>
                            <select name="aside-menu" class="wq-search-aside__select wq-select">
                                <option value="">Пункт меню...</option>
                                <?php foreach ($this->menu as $table => $item):?>

                                    <option value="<?=$this->alias([$this->adminPath => 'show', $table])?>"><?=$item['name'] ?? $table?></option>

                                <?php endforeach;?>
                            </select>
                            <button class="wq-search-aside__button _ibg _btn" type="button">
                                <picture>
                                    <source srcset="<?=$this->getTemplateImg()?>icons/icon-search.webp" type="image/webp">
                                    <img src="<?=$this->getTemplateImg()?>icons/icon-search.png" alt="icon">
                                </picture>
                            </button>
                        </form>
                    </div>

                    <script>

                        document.querySelector('[data-menu-search-form] select').addEventListener('change', function (){

                            if(this.value){

                                location.href = this.value

                            }

                        })
                    </script>

                <?php endif;?>

            </div>
            <nav class="wq-aside-menu__body">
                <?php if($this->menu):?>
                    <ul class="wq-aside-menu__list">
                        <?php foreach ($this->menu as $table => $item):?>
                            <!-- Пункт меню -->
                            <li class="wq-aside-menu__item">
                                <a href="<?=$this->adminPath?>show/<?=$table?>" class="wq-aside-menu__link <?=$table === $this->table ? '_active' : ''?>">
                                    <div class="wq-aside-menu__icon _ibg">
                                        <?php

                                            if(!empty($item['img'])){

                                                $img = $item['img'];

                                            }else{

                                                if(empty($listImages)){

                                                    $listImages = scandir(\AppH::correctPathLtrim($this->getViewsPath(), \App::config()->WEB('img')));

                                                }

                                                if(($indexesArr = preg_grep('/' . $table . '/i', $listImages))){

                                                    $img = $indexesArr[key($indexesArr)];

                                                }else{

                                                    $img = 'pages.png';

                                                }

                                            }

                                        ?>
                                        <picture>
                                            <source srcset="<?=$this->getTemplateImg() . $img?>">
                                            <img src="<?=$this->getTemplateImg() . $img?>" alt="icon" style="filter: brightness(0) invert(1)">
                                        </picture>
                                    </div>
                                    <span class="wq-aside-menu__text"><?=$item['name'] ?? $table?></span>
                                </a>
                            </li>
                            <!-- конец пункт -->
                        <?php endforeach;?>
                    </ul>
                <?php endif;?>

            </nav>
        </aside>

        <div class="wq-wrapper">

            <header class="wq-header">

                <div style="display: none; width: 100vw; height: 100vh; top: 0; left: 0; position: fixed; background: rgba(0,0,0,0.8); z-index: 999; justify-content: center; align-items: center; padding: 20px" class="import-form">
                    <form action="<?=$this->alias([$this->adminPath => 'import'])?>" method="post" enctype="multipart/form-data" class="file-import" style="flex-basis: 600px; background: white; padding: 20px">
                        <label for="">
                            <span>Файл импорта</span>
                            <input type="file" name="import" style="display: block; margin: 10px 0">
                        </label>
                        <input type="submit" value="Загрузить" style="display: inline-block; margin: auto; padding: 5px 8px">
                    </form>
                </div>

                <script>

                    function showImportForm(){

                        let importForm = document.querySelector('.import-form');

                        importForm.style.display = 'flex';

                        importForm.onclick = function(e){

                            if(e.target === this) this.style.display = 'none';

                        }

                    }

                </script>
                <div class="wq-header__menu wq-menu">
                    <nav class="wq-menu__body">
                        <ul class="wq-menu__list">
                            <li class="wq-menu__item">
                                <a href="<?=$this->alias($this->adminPath)?>" class="wq-menu__link _active">Главная</a>
                            </li>
                            <li class="wq-menu__item">
                                <a href="<?=$this->alias()?>" target="_blank" class="wq-menu__link">На сайт</a>
                            </li>

                        </ul>
                        <ul class="wq-menu__list" data-da=".wq-move-header__menu, 768, 0">
                            <?php
                                $nameSpace = str_replace('/', '\\', (new \ReflectionClass($this))->getNamespaceName());
                            ?>
                            <?php if(class_exists($nameSpace . '\ImportController')):?>
                                <li class="wq-menu__item" style="cursor: pointer">
                                    <span class="wq-menu__link" onclick="showImportForm()">Import</span>
                                </li>
                            <?php endif;?>
                            <?php if(class_exists($nameSpace . '\CreatesitemapController')):?>
                                <li class="wq-menu__item">
                                    <a href="<?=$this->adminPath?>createsitemap" class="wq-menu__link">Create sitemap</a>
                                </li>
                            <?php endif;?>
                        </ul>
                    </nav>
                    <div class="wq-header__wrap">
                        <div class="wq-header__user wq-user-header">
                            <span class="wq-user-header__link"><?=$this->userData['name']?></span>
                        </div>
                        <div class="wq-header__theme wq-theme-header">
                            <a href="<?=$this->adminPath?>change-theme" class="wq-theme-header__link _ibg">
                                <picture>
                                    <source srcset="<?=$this->getTemplateImg()?>icons/icon-theme.webp">
                                    <img src="<?=$this->getTemplateImg()?>icons/icon-theme.png" alt="icon">
                                </picture>
                            </a>
                        </div>
                        <div class="wq-header__logout wq-logout-header">
                            <a href="<?=$this->alias(['login' => 'logout'])?>" class="wq-logout-header__link _ibg">
                                <picture>
                                    <source srcset="<?=$this->getTemplateImg()?>icons/icon-logout.webp" type="image/webp">
                                    <img src="<?=$this->getTemplateImg()?>icons/icon-logout.png" alt="icon">
                                </picture>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="wq-header__move wq-move-header">
                    <div class="wq-move-header__menu">
                    </div>
                </div>
                <div class="wq-header__inner">
                    <div class="wq-header__search wq-search-form">
                        <form class="wq-search-form__form" autocomplete="off" action="<?=$this->alias('search')?>">
                            <input type="text" name="search" class="wq-search-form__input" placeholder="Поиск...">
                            <input type="hidden" name="search_table" value="<?=$this->table?>">
                            <button class="wq-search-form__button _ibg _btn">
                                <picture>
                                    <source srcset="<?=$this->getTemplateImg()?>icons/icon-search.webp" type="image/webp">
                                    <img src="<?=$this->getTemplateImg()?>icons/icon-search.png" alt="icon">
                                </picture>
                            </button>
                        </form>
                        <div class="search_res"></div>
                    </div>
                    <?php if($this->multiLanguage && ($this->getController() === 'add' || $this->getController() === 'edit')):?>
                        <select class="language-version" name="language[]" data-language>
                            <option value="">Основной</option>
                            <?php foreach($this->multiLanguage as $lang => $lang_name):?>
                                <option value="<?=str_replace('-', '_', $lang)?>_"><?=$lang_name?></option>
                            <?php endforeach;?>
                        </select>
                    <?php endif;?>
                </div>
            </header>