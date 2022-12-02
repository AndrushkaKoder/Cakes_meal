<?php if(!empty($row)):?>
    <!-- Блок "Фильтры" -->
    <div class="wq-block" data-filter-block>

        <?=$this->render(ADMIN_TEMPLATE . 'include/sorting_block')?>

        <div class="wq-block__wrap">

            <div class="wq-block__title-wrap">
                <h3 class="wq-block__title <?=!empty($this->userData['ROOT']) ? 'sorting-title' : ''?>"><?=$this->translate[$row][0] ?? $row?></h3>
                <p class="wq-block__caption"><?=$this->translate[$row][1] ?? ''?></p>


            <?php

                $content = '<div class="wq-block__link-wrap">
                                    <a href="#" class="wq-block__link __active" data-show="none">Только активные</a>
                                    <a href="#" class="wq-block__link __all" data-show="block">Все</a>
                                </div>
                            </div>';

                $all = true;

            ?>

            <?php if(!empty($this->foreignData[$row])):?>

                <?php foreach ($this->foreignData[$row] as $name => $value):?>

                    <?php if(!empty($value['sub'])):?>

                        <?php

                            $hide = true;

                            ob_start();

                        ?>
                        <!-- Один фильтр -->
                        <div class="wq-block__spoilers wq-spoilers" data-filter-wrap <!--style-->>
                            <div class="wq-spoilers__caption">
                                <div class="wq-spoilers__caption-inner">
                                    <button type="button" class="wq-spoilers__button _btn">
                                        <?=$value['name']?>
                                    </button>
                                    <div>
                                        <input type="text" class="wq-spoilers__input-search search-checkbox" placeholder="найти" data-filter-search>
                                        <span style="cursor: pointer" class="wq-spoilers__link select_all" data-filter-select-all>Выделить все</span>
                                    </div>

                                </div>
                                <button class="wq-spoilers__button wq-spoilers__button_icon _btn">
                                    <div class="wq-spoilers__icon _ibg">
                                        <picture>
                                            <source srcset="<?=PATH . ADMIN_TEMPLATE?>img/icons/icon-arrow.webp" type="image/webp">
                                            <img src="<?=PATH . ADMIN_TEMPLATE?>img/icons/icon-arrow.png" alt="icon">
                                        </picture>
                                    </div>
                                </button>
                            </div>
                            <div class="wq-spoilers__body" data-table="<?=$row?>">
                                <ul class="wq-spoilers__list">
                                    <?php foreach($value['sub'] as $item):?>
                                        <!-- Фильтр checkbox + input -->
                                        <li class="wq-spoilers__item" data-filter-item>
                                            <div class="wq-spoilers__checkbox-inner">
                                                <input id="<?=$row?>-<?=$name?>-<?=$item['id']?>" class="wq-block__checkbox" type="checkbox" name="<?=$row?>[<?=$name?>][<?=$item['id']?>][id]" value="<?=$item['id']?>" <?php if(isset($this->data[$row][$name][$item['id']])){ echo 'checked'; $hide = false;}?>>
                                                <label for="<?=$row?>-<?=$name?>-<?=$item['id']?>" class="wq-block__checkbox-label">
                                                    <span data-filter-name><?= !empty($item['recursive_name']) ? $item['recursive_name'] : $item['name'] ?></span>
                                                </label>
                                            </div>
                                            <?php if(!empty($item[$this->table . '_value'])):?>
                                                <input name="<?=$row?>[<?=$name?>][<?=$item['id']?>][<?=$this->table . '_value'?>]" type="text"
                                                       value="<?=isset($this->data[$row][$name][$item['id']]) && $this->data[$row][$name][$item['id']] !== true ? $this->data[$row][$name][$item['id']] : ''?>" class="wq-spoilers__input">
                                            <?php endif?>
                                            <?php if(!empty($item['properties'])):?>
                                                <?php foreach ($item['properties'] as $k => $prop):?>
                                                    <div class="wq-spoilers__checkbox-inner">
                                                        <input id="<?=$row?>-<?=$name?>-<?=$item['id']?>-properties-<?=$k?>" class="wq-block__checkbox" type="checkbox" name="<?=$row?>[<?=$name?>][<?=$item['id']?>][properties][<?=$k?>]" value="<?=$prop['value']?>" <?php if(isset($this->data[$row][$name][$item['id']]['properties'][$k])) echo 'checked';?>>
                                                        <label for="<?=$row?>-<?=$name?>-<?=$item['id']?>-properties-<?=$k?>" class="wq-block__checkbox-label">
                                                            <span><?= $prop['name'] ?></span>
                                                        </label>
                                                    </div>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                        </li>
                                        <!-- конец Фильтр checkbox + input -->
                                    <?php endforeach;?>
                                </ul>
                            </div>
                        </div>
                        <!-- Конец фильтр -->

                        <?php

                            $block = ob_get_clean();

                            $style = 'style="display:block;"';

                            if(!empty($this->data[$row]) && $hide){

                                $style = 'style="display:none;"';

                                $all = false;

                            }

                            $content .= preg_replace('/<!--style-->/', $style, $block);

                        ?>

                    <?php endif;?>

                <?php endforeach;?>

            <?php endif;?>

            <?php

                $linkClass = $all ? '__all' : '__active';

                echo preg_replace('/' . $linkClass . '/', '_active', $content);

            ?>

        </div>
    </div>
    <!-- Конец блока "Фильтры" -->
<?php endif;?>