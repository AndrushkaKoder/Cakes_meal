<?php if(!empty($row)):?>
    <!-- Блок "Файл" -->
    <div class="wq-block img_wrapper img_container">

        <?=$this->render(ADMIN_TEMPLATE . 'include/sorting_block')?>

        <div class="wq-block__wrap">
            <h3 class="wq-block__title <?=!empty($this->userData['ROOT']) ? 'sorting-title' : ''?>"><?=$this->translate[$row][0] ?? $row?></h3>
            <p class="wq-block__caption"><?=$this->translate[$row][1] ?? ''?></p>
            <div class="wq-block__controls wq-controls">
                <?php if(!empty($this->data[$row]) && $this->showButtons()):?>
                    <a class="wq-controls__button wq-button wq-button_valencia _btn wq-delete" href="<?=$this->adminPath . 'delete/' . $this->table . '/' . $this->data[$this->columns['id_row']] . '/' . $row . '/' . base64_encode($this->data[$row])?>">
                        удалить
                    </a>
                <?php endif;?>
                <?php if($this->showButtons()):?>
                    <div class="wq-controls__button wq-button wq-button_lavender wq-button_file _btn">
                        <input type="file" name="<?=$row?>" class="wq-block__input-file">
                        выберите файл
                    </div>
                <?php endif;?>
            </div>
            <?php

                $fileName = 'Файл не выбран';

                if(!empty($this->data[$row])){

                    $fileArr = explode('/',$this->data[$row]);

                    $fileName = array_pop($fileArr);

                    $fileName = '<a href="' . PATH . UPLOAD_DIR . $this->data[$row] . '">' . $fileName . '</a>';

                }

            ?>

            <div class="wq-block__input img_show main_img_show"><?=$fileName?></div>

        </div>
    </div>
    <!-- Конец блока "Файл" -->
<?php endif;?>