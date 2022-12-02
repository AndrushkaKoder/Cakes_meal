<?php if(!empty($row)):?>
    <!-- Блок "Описание" -->
    <div class="wq-block" data-tiny-wrapper>

        <?=$this->render(ADMIN_TEMPLATE . 'include/sorting_block')?>

        <div class="wq-block__wrap">
            <h3 class="wq-block__title <?=!empty($this->userData['ROOT']) ? 'sorting-title' : ''?>"><?=$this->translate[$row][0] ?? $row?></h3>
            <p class="wq-block__caption"><?=$this->translate[$row][1] ?? ''?>&nbsp;<span></span></p>

            <?php

                $checked = !empty($class) && $class === 'full' ? 'checked' : '';

                $value = $_SESSION['res'][$row] ?? ($this->data[$row] ?? '');

                if(!$checked && $value){

                    if(preg_match('/<\/[^>]+>/', $value)){

                        $checked = 'checked';

                    }

                }

            ?>
            <div class="wq-block__checkbox-inner">
                <input id="<?=$row?>-tinyMceInit" class="wq-block__checkbox tinyMceInit" type="checkbox" <?=$checked?>>
                <label for="<?=$row?>-tinyMceInit" class="wq-block__checkbox-label">
                    <span>Визуальный режим</span>
                </label>
            </div>
            <textarea data-type="<?=$this->columns[$row]['Type']?>" class="wq-block__textarea" name="<?=$row?>"><?=htmlspecialchars($value)?></textarea>
        </div>
    </div>
    <!-- Конец блока "Описание" -->
<?php endif;?>
