<?php if(!empty($row)):?>
    <!-- Блок "Тизеры" -->
    <div class="wq-block">

        <?=$this->render(ADMIN_TEMPLATE . 'include/sorting_block')?>

        <div class="wq-block__wrap addedlist-container">
            <h3 class="wq-block__title <?=!empty($this->userData['ROOT']) ? 'sorting-title' : ''?>"><?=$this->translate[$row][0] ?? $row?></h3>
            <p class="wq-block__caption"><?=$this->translate[$row][1] ?? ''?></p>
            <div class="addedlist-wrap">
                <?php
                $index = -1;
                ?>

                <?php if(!empty($this->data[$row]) || !empty($_SESSION['res'][$row])):?>
                    <?php

                    $rowArr = !empty($_SESSION['res'][$row]) ? $_SESSION['res'][$row] : json_decode($this->data[$row], true);

                    ?>

                    <?php foreach ($rowArr as $i => $value):?>

                        <?php
                        if(empty($value['name']) && empty($value['value']))
                            continue;

                        $index++

                        ?>

                        <div class="addedlist-item" data-tiny-wrapper>

                            <h2 class="wq-block__title-h2">Название</h2>
                            <input type="text" name="<?=$row?>[<?=$index?>][name]" class="wq-block__input wq-block_mb-small" value="<?=htmlspecialchars($value['name'])?>">
                            <h2 class="wq-block__title-h2">Значение</h2>

                            <?php

                                $checked = !empty($class) && $class === 'full' ? 'checked' : '';

                                if(!$checked && $value['value']){

                                    if(preg_match('/<\/[^>]+>/', $value['value'])){

                                        $checked = 'checked';

                                    }

                                }

                            ?>

                            <div class="wq-block__checkbox-inner">
                                <input id="tiny-<?=$row?>-<?=$index?>" class="wq-block__checkbox tinyMceInit" type="checkbox" <?=$checked?>>
                                <label for="tiny-<?=$row?>-<?=$index?>" class="wq-block__checkbox-label">
                                    <span>Визуальный режим</span>
                                </label>
                            </div>
                            <textarea name="<?=$row?>[<?=$index?>][value]" class="wq-block__textarea wq-block__textarea_short wq-block_mb-small"><?=htmlspecialchars($value['value'])?></textarea>

                        </div>

                    <?php endforeach;?>

                <?php endif;?>

                <?php if((empty($this->data[$row]) && empty($_SESSION['res'][$row])) || $index === -1):?>
                    <div class="addedlist-item" data-tiny-wrapper>

                        <h2 class="wq-block__title-h2">Название</h2>
                        <input type="text" name="<?=$row?>[0][name]" class="wq-block__input wq-block_mb-small">
                        <h2 class="wq-block__title-h2">Значение</h2>
                        <?php

                            $checked = !empty($class) && $class === 'full' ? 'checked' : '';

                        ?>
                        <div class="wq-block__checkbox-inner">
                            <input id="tiny-<?=$row?>-0" class="wq-block__checkbox tinyMceInit" type="checkbox" <?=$checked?>>
                            <label for="tiny-<?=$row?>-0" class="wq-block__checkbox-label">
                                <span>Визуальный режим</span>
                            </label>
                        </div>
                        <textarea name="<?=$row?>[0][value]" class="wq-block__textarea wq-block__textarea_short wq-block_mb-small"></textarea>

                    </div>
                <?php endif;?>
            </div>

            <?php if($this->showButtons()):?>
                <div class="wq-block__controls wq-controls">
                    <button type="button" class="wq-controls__button wq-button wq-button_fern _btn addedlist-add">
                        добавить элемент списка
                    </button>
                </div>
            <?php endif;?>
        </div>
    </div>
    <!-- Конец блока "Тизеры" -->
<?php endif;?>
