<?php if(!empty($row)):?>
    <div class="wq-block">

        <?=$this->render(ADMIN_TEMPLATE . 'include/sorting_block', ['notShowSortingBlock' => $h3 ?? null])?>

        <div class="wq-block__wrap">
            <h3 class="wq-block__title<?=!empty($h3) ? '-h3' : ''?> <?=!empty($this->userData['ROOT']) ? 'sorting-title' : ''?>"><?= $this->translate[$row][0] ?? $row?></h3>
            <?php if(empty($h3)):?>
                <p class="wq-block__caption"><?= $this->translate[$row][1] ?? ''?></p>
            <?php endif;?>
            <div class="wq-block__select" data-select-wrap>
                <select name="<?=$row?>" class="wq-select" <?=$row === 'parent_id' ? 'data-exclude-custom-select' : ''?>>
                    <?php if(!empty($this->foreignData[$row])):?>
                        <?php $this->showSelectParents($this->foreignData[$row], $row);?>
                    <?php endif;?>
                </select>
            </div>
        </div>
    </div>

<?php endif;?>