<main class="wq-main">

    <div class="wq-content">
        <div class="wq-content__form wq-main-form">

            <?=$this->buttons?>

            <div class="wq-main-form__full">
                <!-- Блок "Товары" -->
                <div class="wq-block wq-goods">
                    <div class="wq-goods__wrap">
                        <h1 class="wq-block__title">
                            <?=$h1 ?? ($this->menu[$this->table]['name'] ?? $this->table)?>
                        </h1>
                        <?php if($this->foreignData):?>
                            <div class="wq-goods__controls">
                                <div class="wq-goods__caption">
                                    <h2 class="wq-block__title-h2">Фильтры</h2>
                                    <a href="<?=$this->adminPath?>show/<?=$this->table?>" class="wq-controls__button wq-button wq-button_lavender _btn">
                                        сбросить фильтры
                                    </a>
                                </div>
                                <div class="wq-goods__selects wq-selects-goods" data-filters>
                                    <ul class="wq-selects-goods__list">
                                        <!-- Один select -->

                                        <?php foreach ($this->foreignData as $row => $item){

                                            echo '<li class="wq-selects-goods__item">';

                                            if(empty($item[0]['id']) || strtolower($item[0]['id']) !== 'null'){

                                                $emptyName = \core\base\settings\Settings::get('rootItems');

                                                $emptyName = !empty($emptyName['name']) ? $emptyName['name'] : 'Нет';

                                                $this->foreignData[$row] = ['NULL' => ['id' => 'NULL', 'name' => $emptyName]] + $item;

                                            }

                                            $path = $this->defaultTemplatePath;

                                            $h3 = true;

                                            if (!@include $_SERVER['DOCUMENT_ROOT']. $path . 'select.php') {
                                                throw new \core\base\exceptions\RouteException('Не найден шаблон ' . $_SERVER['DOCUMENT_ROOT']. $path . 'select.php');
                                            }

                                            echo '</li>';
                                        }

                                        ?>
                                        <!-- Один select -->

                                    </ul>
                                </div>
                            </div>
                        <?php endif;?>

                        <?php if(!empty($this->data)):?>

                            <?php if(!empty($this->pagination)):?>
                                <div class="wq-main-form__pagination wq-pagination">
                                    <ul class="wq-pagination__list">
                                <?php $this->pagination($this->pagination, ADMIN_TEMPLATE . 'include/pagination')?>
                                    </ul>
                                </div>
                            <?php endif;?>

                            <div class="wq-goods__items">

                                <?php $this->recursiveOutput($this->data);?>

                            </div>

                            <?php if(!empty($this->pagination)):?>
                                <div class="wq-main-form__pagination wq-pagination">
                                    <ul class="wq-pagination__list">
                                        <?php $this->pagination($this->pagination, ADMIN_TEMPLATE . 'include/pagination')?>
                                    </ul>
                                </div>
                            <?php endif;?>

                        <?php endif;?>
                    </div>
                </div>
                <!-- Конец блока "Товары" -->
            </div>

            <?=$this->buttons?>

        </div>
    </div>

</main>