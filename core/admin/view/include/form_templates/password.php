<?php if(!empty($row)):?>
    <!-- Блок "Пароль" -->
    <div class="wq-block">

        <?=$this->render(ADMIN_TEMPLATE . 'include/sorting_block')?>

        <div class="wq-block__wrap">
            <h3 class="wq-block__title <?=!empty($this->userData['ROOT']) ? 'sorting-title' : ''?>"><?=$this->translate[$row][0] ?? $row?></h3>
            <p class="wq-block__caption"><?=$this->translate[$row][1] ?? ''?></p>
            <input name="<?=$row?>" value="<?php echo isset($_SESSION['res'][$row]) ? htmlspecialchars($_SESSION['res'][$row]) : '';?>" type="password" class="wq-block__input wq-block_mb-big" onfocus="this.removeAttribute('readonly')" readonly>
        </div>
    </div>
    <!-- Конец блока "Пароль" -->
<?php endif?>