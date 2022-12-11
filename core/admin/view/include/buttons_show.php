<div class="wq-main-form__controls wq-controls">
    <?php if((!empty($this->userData['ROOT']) || (!isset($this->userData['credentials']) ||
            !is_array($this->userData['credentials']) ||
            isset($this->userData['credentials'][$this->table]['add']))) && empty($no_add) && $this->getController() === 'show'):?>

        <a href="<?=$this->alias(['add' => $this->table])?>" class="wq-controls__button wq-button wq-button_fern _btn">
            добавить
        </a>

    <?php endif;?>

    <a href="<?=$_SERVER['HTTP_REFERER'] ?? ''?>" class="wq-controls__button wq-button wq-button_havelock _btn">
        назад
    </a>

    <?php if(!empty($this->userData['ROOT']) && !empty($this->columns['menu_position']) && $this->getController() === 'show'):?>
        <a href="<?=$this->alias(['show' => $this->table], ['revision-menu_position' => 'true'])?>?" class="wq-controls__button wq-button wq-button_valencia _btn revision-menu_position" title="Проводится пересчет позиций вывода во всем разделе, в случае возникновения ошибки. ПРОСТО ТАК НЕ БАЛОВАТЬСЯ!!!">
            Ревизия позиций вывода
        </a>
    <?php endif;?>

</div>
