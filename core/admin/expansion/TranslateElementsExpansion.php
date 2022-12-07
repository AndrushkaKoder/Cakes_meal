<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 07.03.2019
 * Time: 13:13
 */

namespace core\admin\expansion;

class TranslateElementsExpansion
{

    public function expansion(){

        //$this->translate['name'][0] = 'Текст элемента на странице';

        $this->translate['alias'][0] = 'Символьный код элемента';

        $this->translate['alias'][1] = 'Заполняется автоматически';

        $key = array_search('alias', $this->templateArr['text']);

        unset($this->templateArr['text'][$key]);

        $this->templateArr['text_disabled'][] = 'alias';

    }

}