<?php if(!empty($row)):?>
    <!-- Блок "Галлерея изображений" -->
    <div class="wq-block">

        <?=$this->render(ADMIN_TEMPLATE . 'include/sorting_block')?>

        <div class="wq-block__wrap">
            <h3 class="wq-block__title <?=!empty($this->userData['ROOT']) ? 'sorting-title' : ''?>"><?=$this->translate[$row][0] ?? $row?></h3>
            <p class="wq-block__caption"><?=$this->translate[$row][1] ?? ''?></p>
            <div class="wq-block__wrap-gallery img_wrapper gallery_container">
                <div class="wq-block__img-gallery wq-button__wrapper">
                    <?php if($this->showButtons()):?>
                        <div class="wq-controls__button wq-button wq-button__gallery wq-button_file _btn">
                            <div class="wq-button__gallery-wrap _ibg">
                                <picture>
                                    <source srcset="<?=PATH . ADMIN_TEMPLATE?>img/icons/icon-plus.webp" type="image/webp">
                                    <img src="<?=PATH . ADMIN_TEMPLATE?>img/icons/icon-plus.png" alt="icon">
                                </picture>
                            </div>
                            <input type="file" name="<?=$row?>[]" multiple class="wq-block__input-file" accept="image/*,image/jpeg,image/png,image/gif,image/svg">
                        </div>
                    <?php endif;?>

                </div>
                <?php if(!empty($this->data[$row])):?>
                    <?php foreach (json_decode($this->data[$row], true) as $img):?>
                        <?php if($this->showButtons()):?>
                            <a href="<?=$this->adminPath . 'delete/' . $this->table . '/' . $this->data[$this->columns['id_row']] . '/' . $row . '/' . base64_encode($img)?>" class="wq-block__img-gallery _ibg wq-delete">
                                <img src="<?=PATH . UPLOAD_DIR . $img?>?v1.<?=(str_replace(' ', '_', microtime()))?>" alt="image">
                            </a>
                        <?php else:?>
                            <span class="wq-block__img-gallery _ibg">
                                <img src="<?=PATH . UPLOAD_DIR . $img?>?v1.<?=(str_replace(' ', '_', microtime()))?>" alt="_image">
                            </span>
                        <?php endif;?>
                    <?php endforeach;?>
                    <?php if($this->showButtons()):?>
                        <?php
                        for ($i = 0; $i < 2; $i++){
                            echo '<div class="wq-block__img-gallery _ibg empty_container"></div>';
                        }
                        ?>
                    <?php endif;?>
                <?php else:?>
                    <?php
                        for ($i = 0; $i < 13; $i++){
                            echo '<div class="wq-block__img-gallery _ibg empty_container"></div>';
                        }
                    ?>
                <?php endif;?>

            </div>
        </div>
    </div>
    <!-- Конец блока "Галлерея изображений" -->
<?php endif;?>