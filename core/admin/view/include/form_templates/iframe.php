<?php if(!empty($row) && !empty($this->data)):?>
    <!-- Блок "iframe" -->
    <div class="wq-block">

        <?=$this->render($this->getViewsPath() . 'include/sorting_block')?>

        <div class="wq-block__wrap">
            <h3 class="wq-block__title <?=!empty($this->userData['ROOT']) ? 'sorting-title' : ''?>"><?=$this->translate[$row][0] ?? $row?></h3>
            <p class="wq-block__caption"><?=$this->translate[$row][1] ?? ''?>&nbsp;<span></span></p>
            <span class="wq-controls__button wq-button wq-button_havelock _btn" style="cursor: pointer" data-show-iframe="iframe-<?=$row?>-<?=$this->data[$this->columns['id_row']]?>">Посмотреть</span>
        </div>
        <div class="wq-iframe-container" style="display:none; z-index:9999; background: rgba(0,0,0,0.4); position: fixed; top:0; left: 0; width: 100vw; height: 100vh; padding: 60px 40px">
            <iframe id="iframe-<?=$row?>-<?=$this->data[$this->columns['id_row']]?>" style="width: 100%; height: 100%" src="<?=$this->alias(['show' => $row], ['filter[parent_id]' => $this->data[$this->columns['id_row']], 'iframeMode' => 'true'])?>" frameborder="0" allow="fullscreen"></iframe>
        </div>
        <script>

            document.addEventListener('DOMContentLoaded', () => {

                let iframe = document.querySelector('#iframe-<?=$row?>-<?=$this->data[$this->columns['id_row']]?>')

                let iframeShow = document.querySelector('[data-show-iframe="iframe-<?=$row?>-<?=$this->data[$this->columns['id_row']]?>"]')

                iframeShow.addEventListener('click', () => {

                    iframe.parentElement.style.display = 'block'

                    let parentIdSelect = document.querySelector('[name="parent_id"]')

                    if(parentIdSelect){

                        Ajax({
                            data:{
                                ajax: 'set_parent_id',
                                table: '<?=$row?>',
                                id: '<?=$this->data[$this->columns['id_row']]?>'
                            }
                        })

                    }

                })

                iframe.parentElement.addEventListener('click', e => {

                    if(e.target === iframe.parentElement){

                        iframe.parentElement.style.display = 'none'

                    }

                })

                iframe.onload = () => {

                    let hideBlocks = ['.wq-aside-menu', 'header', 'footer', '.wq-goods__controls']

                    hideBlocks.forEach(item => {

                        iframe.contentWindow.document.querySelectorAll(item).forEach(block => {

                            block.style.display = 'none'

                        })

                    })

                }

            })

        </script>
    </div>
    <!-- Конец блока "iframe" -->
<?php endif?>