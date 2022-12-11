<div class="wq-main-form__controls wq-controls">

    <?php if(($this->getController() === 'add' && $this->showButtons('add')) || ($this->getController() === 'edit' && $this->showButtons('edit'))):?>
        <button type="submit" class="wq-controls__button wq-button wq-button_anzac _btn">
            Сохранить
        </button>
    <?php endif;?>

    <?php if($this->showButtons('add') && $this->showButtons('edit') && empty($no_add)):?>
        <button type="submit" name="add_new_element" class="wq-controls__button wq-button wq-button_buddha _btn">
            Сохранить и добавить
        </button>
    <?php elseif ($this->showButtons('add') && empty($no_add)):?>
        <a href="<?=$this->alias(['add' => $this->table])?>" class="wq-controls__button wq-button wq-button_fern _btn">
            добавить
        </a>
    <?php endif;?>

    <?php if(empty($no_delete) && $this->data && $this->showButtons('delete')):?>
        <a href="<?=$this->alias(['delete', $this->table => $this->data[$this->columns['id_row']]])?>" class="wq-controls__button wq-button wq-button_valencia _btn wq-delete">
            Удалить
        </a>
    <?php endif;?>

    <?php

        $backAlias = $this->alias(['show' => $this->table]);

        if(!empty($_SERVER['HTTP_REFERER']) && preg_match('/iframemode=/i', $_SERVER['HTTP_REFERER'])){

            $backAlias = $_SERVER['HTTP_REFERER'];

        }

    ?>

    <a href="<?=$backAlias?>" class="wq-controls__button wq-button wq-button_havelock _btn">
        Назад
    </a>
</div>
