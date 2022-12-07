<?php if(!empty($row)):?>
    <!-- Блок "Дата" -->
    <div class="wq-block">

        <?=$this->render($this->getViewsPath() . 'include/sorting_block')?>

        <div class="wq-block__wrap">
            <h3 class="wq-block__title <?=!empty($this->userData['ROOT']) ? 'sorting-title' : ''?>"><?=$this->translate[$row][0] ?? $row?></h3>
            <p class="wq-block__caption"><?=$this->translate[$row][1] ?? ''?></p>
            <input name="<?=$row?>" data-type="<?=$this->columns[$row]['Type']?>" value="<?= isset($_SESSION['res'][$row]) ? htmlspecialchars($_SESSION['res'][$row]) : (isset($this->data[$row]) ? htmlspecialchars($this->data[$row]) : '')?>" type="datetime-local" class="wq-block__input wq-block_mb-big">
        </div>
    </div>
    <!-- Конец блока "Дата" -->
<?php endif?>