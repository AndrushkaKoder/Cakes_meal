<?php
namespace core\admin\controller;

use settings\Settings;

class SearchController extends BaseAdmin{

    protected function inputData(){

        if(!$this->userData) $this->execBase();

        $text = \AppH::clearStr($_GET['search']);

        if(!$text) 
            \AppH::redirect();

        $table = \AppH::clearStr($_GET['search_table']);

        $pages = [];

        $pages['qty'] = $this->countElements;

        $pages['qty_links'] = $this->linksCounter;

        $pages['page'] = !empty($_GET['page']) ? \AppH::clearNum($_GET['page']) : 1;

        !$pages['page'] && $pages['pagination']['page'] = 1;

        $this->data = $this->model->adminSearch($text, $table, $pages);

        $this->pagination = $this->model->getPagination();

        $this->template = ADMIN_TEMPLATE . 'show';

        $h1 = 'Результаты поиска по запросу ' . $text;

        return compact('h1');

    }

}