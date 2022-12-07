<?php if(!empty($row)):?>
    <!-- Блок "Выбор цвета" -->
    <div class="wq-block">

        <?=$this->render($this->getViewsPath() . 'include/sorting_block')?>

        <div class="wq-block__wrap wq-block__wrap_cp" data-cp="cp-1">
            <h3 class="wq-block__title <?=!empty($this->userData['ROOT']) ? 'sorting-title' : ''?>"><?=$this->translate[$row][0] ?? $row?></h3>
            <p class="wq-block__caption"><?=$this->translate[$row][1] ?? ''?></p>
            <div class="wq-block__cp-controls wq-block_mb-small">
                <input name="<?=$row?>" type="text" class="wq-block__input" value="<?= isset($_SESSION['res'][$row]) ? htmlspecialchars($_SESSION['res'][$row]) : (isset($this->data[$row]) ? htmlspecialchars($this->data[$row]) : '')?>">
                <button type="submit" class="wq-button wq-button_fern _btn" data-cp-btn="cp-1">
                    Добавить
                </button>
            </div>
            <div class="wq-block__cp-wrap"></div>
            <div class="wq-block__cp-popup">
                <div class="wq-block__cp-inner">
                    <div class="wq-block__cp-type">
                        <div class="wq-block__radio-inner">
                            <input id="hex-<?=$row?>" class="wq-block__radio" type="radio" value="hex" checked>
                            <label for="hex-<?=$row?>" class="wq-block__radio-label">
                                <span>HEX</span>
                            </label>
                        </div>
                        <input type="text" class="wq-block__input wq-block__input_type color" value="#fff">
                    </div>
                    <div class="wq-block__cp-type">
                        <div class="wq-block__radio-inner">
                            <input id="rgb-<?=$row?>" class="wq-block__radio" type="radio" value="rgb">
                            <label for="rgb-<?=$row?>" class="wq-block__radio-label">
                                <span>RGB</span>
                            </label>
                        </div>
                        <input type="text" class="wq-block__input wq-block__input_type color" value="rgb(162, 63, 3)">
                    </div>
                </div>
                <div class="wq-main-form__controls wq-controls">
                    <button type="submit" class="wq-controls__button wq-button wq-button_anzac wq-button__cp-save _btn">
                        Cохранить
                    </button>
                    <button type="submit" class="wq-controls__button wq-button wq-button_havelock wq-button__cp-reset _btn">
                        Отмена
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Конец блока "Выбор цвета" -->
<?php endif;?>