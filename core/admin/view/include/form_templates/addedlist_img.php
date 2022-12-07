<?php if(!empty($row)):?>
    <!-- Блок "Изображение + Текст" -->
    <div class="wq-block">

        <?=$this->render($this->getViewsPath() . 'include/sorting_block')?>

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
                            if(empty($value['name']) && empty($value['value']) && empty($value['file']))
                                continue;

                            $index++

                        ?>

                        <div class="addedlist-item img_container" data-tiny-wrapper>

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
                            <div class="img_wrapper">
                                <h2 class="wq-block__title-h2">Файл</h2>
                                <div class="wq-block__controls wq-controls wq-block_mb-small">
                                    <input type="hidden" name="<?=$row?>[<?=$index?>][file]" value="<?=$value['file']?>">
                                    <?php if($this->showButtons()):?>
                                        <div class="wq-controls__button wq-button wq-button_lavender wq-button_file _btn">
                                            <input id="<?=$row?>-<?=$index?>" type="file" name="<?=$row?>[<?=$index?>]" class="wq-block__input-file single_img">
                                            выбрать
                                        </div>
                                    <?php endif;?>
                                    <?php if($value['file'] && $this->showButtons()):?>
                                        <a style="color:black" href="<?=$this->alias(['delete', $this->table => $this->data[$this->columns['id_row']], $row => base64_encode($value['file'])])?>"
                                           class="wq-controls__button wq-button wq-button_valencia _btn wq-delete">
                                            <span>Удалить</span>
                                        </a>
                                    <?php endif;?>
                                </div>
                                <div class="wq-block__img-view wq-block__img-small wq-block_mb-big _ibg" style="padding: 0">
                                    <div class="img_show main_img_show" style="max-height: 180px">
                                        <?php if($value['file']):?>

                                            <?php
                                                $fileArr = explode('/',$value['file']);

                                                $fileName = array_pop($fileArr);

                                                $fileName = '<a href="' . $this->img($value['file']) . '">' . $fileName . '</a>';

                                                $info = mime_content_type(\AppH::correctPathTrim(\App::FULL_PATH(), \App::config()->WEB('upload_dir'), $value['file']));

                                            if($info){

                                                if(preg_match('/image/i', $info)){

                                                    $wrapClass = 'wq-block__img-view';

                                                    $fileName = '<img src="' . $this->img($value['file']) .
                                                        '?v1.' . (str_replace(' ', '_', microtime())) .
                                                        '" alt="service" ' . (preg_match('/\.svg$/', $value['file']) ? 'style="min-width: 320px"' : '') . '>';

                                                }elseif (preg_match('/video/i', $info)){

                                                    $wrapClass = 'wq-block__img-view';

                                                    $fileName = '<video src="' . $this->img($value['file']) .'" controls="controls"></video>';

                                                }

                                            }


                                            ?>

                                            <?=$fileName?>

                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>


                        </div>

                    <?php endforeach;?>

                <?php endif;?>

                <?php if((empty($this->data[$row]) && empty($_SESSION['res'][$row])) || $index === -1):?>
                    <div class="addedlist-item img_container" data-tiny-wrapper>

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
                        <div class="img_wrapper">
                            <h2 class="wq-block__title-h2">Файл</h2>
                            <div class="wq-block__controls wq-controls wq-block_mb-small">
                                <input type="hidden" name="<?=$row?>[0][file]" value="">
                                <?php if($this->showButtons()):?>
                                    <div class="wq-controls__button wq-button wq-button_lavender wq-button_file _btn">
                                        <input id="<?=$row?>-<?=$index?>" type="file" name="<?=$row?>[0]" class="wq-block__input-file single_img">
                                        выбрать
                                    </div>
                                <?php endif;?>
                            </div>
                            <div class="wq-block__img-view wq-block__img-small wq-block_mb-big _ibg">
                                <div class="img_show main_img_show">

                                </div>
                            </div>
                        </div>


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
    <!-- Конец блока "Изображение + Текст" -->
<?php endif;?>
