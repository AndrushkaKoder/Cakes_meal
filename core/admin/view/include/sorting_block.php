<?php if(empty($notShowSortingBlock)):?>
    <?php if(!empty($this->userData['ROOT']) || method_exists($this->model, 'setTableParameters')):?>
        <div class="wq-block__move">
            <div class="wq-block__icon _ibg">
                <picture><source srcset="<?=$this->getTemplateImg()?>icons/icon-move.webp" type="image/webp">
                    <img src="<?=$this->getTemplateImg()?>icons/icon-move.png" alt="icon">
                </picture>
            </div>
        </div>
    <?php endif;?>
<?php endif;?>
