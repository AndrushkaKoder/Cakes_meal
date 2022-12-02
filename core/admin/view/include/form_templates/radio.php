<?php if(!empty($row)):?>
    <!-- Блок "Единица измерения по умолчанию" -->
    <div class="wq-block">

        <?=$this->render(ADMIN_TEMPLATE . 'include/sorting_block')?>

        <div class="wq-block__wrap">
            <h3 class="wq-block__title <?=!empty($this->userData['ROOT']) ? 'sorting-title' : ''?>"><?=$this->translate[$row][0] ?? $row?></h3>
            <p class="wq-block__caption"><?=$this->translate[$row][1] ?? ''?></p>
            <?php foreach($this->foreignData[$row] as $key => $item):?>
                <?php if(is_int($key)):?>
                    <div class="wq-block__radio-inner">
                        <input id="<?=$row?>-<?=$key?>" class="wq-block__radio" type="radio" name="<?=$row?>"
                            <?php if(isset($this->data[$row]) && $this->data[$row] == $key) echo 'checked';
                            elseif(!isset($this->data[$row]) && $this->foreignData[$row]['default'] == $item) echo 'checked';?> value="<?=$key?>">
                        <label for="<?=$row?>-<?=$key?>" class="wq-block__radio-label">
                            <span><?=$item?></span>
                        </label>
                    </div>
                <?php endif;?>
            <?php endforeach;?>
        </div>
    </div>
    <!-- Конец блока "Единица измерения по умолчанию" -->
<?php endif;?>