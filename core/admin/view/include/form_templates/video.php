<?php if(!empty($row)):?>
    <!-- Блок "Видео" -->
    <div class="wq-block img_container">

        <?=$this->render(ADMIN_TEMPLATE . 'include/sorting_block')?>

        <div class="wq-block__wrap img_wrapper">
            <h3 class="wq-block__title <?=!empty($this->userData['ROOT']) ? 'sorting-title' : ''?>"><?=$this->translate[$row][0] ?? $row?></h3>
            <p class="wq-block__caption"><?=$this->translate[$row][1] ?? ''?></p>

            <div class="wq-block__controls wq-controls">
                <div class="wq-controls__button wq-button wq-button_lavender wq-button_file _btn">
                    <?php if($this->showButtons()):?>
                        <input type="file" name="<?=$row?>" class="wq-block__input-file single_img">
                    <?php endif;?>
                    выбрать
                </div>
                <?php if(!empty($this->data[$row]) && $this->showButtons()):?>
                    <a href="<?=$this->adminPath . 'delete/' . $this->table . '/' . $this->data[$this->columns['id_row']] . '/' . $row . '/' . base64_encode($this->data[$row])?>" class="wq-controls__button wq-button wq-button_valencia _btn wq-delete">
                        удалить
                    </a>
                <?php endif;?>
            </div>
            <div class="wq-block__img-view _ibg img_show main_img_show">
                <?php if(!empty($this->data[$row])):?>
                    <video src="<?=PATH.UPLOAD_DIR.$this->data[$row]?>" controls="controls"></video>
                <?php endif;?>
            </div>
        </div>
    </div>
    <!-- Конец блока "Видео" -->
<?php endif;?>