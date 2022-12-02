<main class="wq-main">

    <div class="wq-content">
        <form id="main-form" action="<?=$this->adminPath . $this->action?>" enctype="multipart/form-data" method="post" class="wq-content__form wq-main-form">

            <?=$this->buttons?>

            <?php if(!empty($this->data)):?>

                <input type="hidden" id="tableId" name="<?=$this->columns['id_row']?>" value="<?=$this->data[$this->columns['id_row']]?>">

            <?php endif;?>

            <input type="hidden" name="table" value="<?=$this->table?>">

            <?=!empty($_SESSION['checked_parents'][$this->table]) ? '<input type="hidden" value="' . $_SESSION['checked_parents'][$this->table] . '" id="data-checked-parents" >' : ''?>

            <?php

                foreach($this->blocks as $class => $block){

                    if(is_int($class)) $class = 'grid';

                    echo '<div class="sort_panel wq-main-form__' . $class . '">';

                    if($block){

                        foreach ($block as $row) {

                            foreach ($this->templateArr as $type => $items) {

                                $existsInTemplate = (bool)array_filter($items, function ($v) use($row){

                                    return (!is_array($v) && $v == $row) || (is_array($v) && in_array($row, $v));

                                }, ARRAY_FILTER_USE_BOTH);

                                if ($existsInTemplate) {

                                    $path = $this->defaultTemplatePath;

                                    if (!@include $_SERVER['DOCUMENT_ROOT']. $path . $type . '.php') {

                                        throw new \core\base\exceptions\RouteException('Не найден шаблон ' . $_SERVER['DOCUMENT_ROOT']. $path . $type . '.php');

                                    }

                                    break;

                                }

                            }
                        }
                    }

                    echo '</div>';

                }

            ?>

            <?=$this->buttons?>

        </form>
    </div>

</main>