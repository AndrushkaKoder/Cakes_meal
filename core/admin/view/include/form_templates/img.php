<?php if(!empty($row)):?>
    <!-- Блок "Изображение" -->
    <div class="wq-block img_container">

        <?=$this->render(ADMIN_TEMPLATE . 'include/sorting_block')?>

        <div class="wq-block__wrap img_wrapper">
            <h3 class="wq-block__title <?=!empty($this->userData['ROOT']) ? 'sorting-title' : ''?>"><?=$this->translate[$row][0] ?? $row?></h3>
            <p class="wq-block__caption"><?=$this->translate[$row][1] ?? ''?></p>

            <div class="wq-block__controls wq-controls">
                <div class="wq-controls__button wq-button wq-button_lavender wq-button_file _btn">
                    <?php if($this->showButtons()):?>
                        <input type="file" name="<?=$row?>" class="wq-block__input-file single_img" accept="image/*,image/jpeg,image/png,image/gif,image/svg">
                    <?php endif;?>
                    выбрать
                </div>
                <?php if(!empty($this->data[$row]) && $this->showButtons()):?>
                    <a href="<?=$this->adminPath . 'delete/' . $this->table . '/' . $this->data[$this->columns['id_row']] . '/' . $row . '/' . base64_encode($this->data[$row])?>" class="wq-controls__button wq-button wq-button_valencia _btn wq-delete">
                        удалить
                    </a>
                <?php endif;?>
            </div>
            <?php

            $wrapClass = 'wq-block__input';

            $fileName = 'Файл не выбран';

            if(!empty($this->data[$row])){

                $fileArr = explode('/',$this->data[$row]);

                $fileName = array_pop($fileArr);

                $fileName = '<a href="' . PATH . UPLOAD_DIR . $this->data[$row] . '">' . $fileName . '</a>';

                $info = mime_content_type($_SERVER['DOCUMENT_ROOT'] . PATH.UPLOAD_DIR . $this->data[$row]);

                if($info){

                    if(preg_match('/image/i', $info)){

                        $wrapClass = 'wq-block__img-view';

                        $fileName = '<img src="' . PATH . UPLOAD_DIR . $this->data[$row] . '?v1.' . (str_replace(' ', '_', microtime())) . '" alt="service" ' . '" alt="service" ' . (preg_match('/\.svg$/', $this->data[$row]) ? 'style="min-width: 320px"' : '') . '>';

                    }elseif (preg_match('/video/i', $info)){

                        $wrapClass = 'wq-block__img-view';

                        $fileName = '<video src="' . PATH.UPLOAD_DIR.$this->data[$row] .'" controls="controls"></video>';

                    }

                }

            }

            ?>
            <div class="<?=$wrapClass?> _ibg img_show main_img_show">
                <?=$fileName?>
            </div>
        </div>
    </div>
    <!-- Конец блока "Изображение" -->
<?php endif;?>