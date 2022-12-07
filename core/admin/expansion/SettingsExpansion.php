<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 07.03.2019
 * Time: 13:13
 */

namespace core\admin\expansion;

class SettingsExpansion
{

    public function expansion(){
        $no_add = true;
        $no_delete = true;

        $this->translate['img'] = ['Логотип компании'];
        $this->translate['gallery_img'] = ['Логотипы торговых марок'];
        $this->translate['background_img'] = ['Изображение заднего фона по умолчанию'];

        return compact('no_add', 'no_delete');
    }

}