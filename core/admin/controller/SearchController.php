<?php
namespace webQAdmin\controller;

use webQAdminSettings\Settings;

class SearchController extends BaseAdmin{

    protected function inputData(){

        if(!$this->userData) $this->execBase();

        $text = \WqH::clearStr($_GET['search']);

        if(!$text) 
            \WqH::redirect();

        $table = \WqH::clearStr($_GET['search_table']);

        $pages = [];

        $pages['qty'] = $this->countElements;

        $pages['qty_links'] = $this->linksCounter;

        $pages['page'] = !empty($_GET['page']) ? \WqH::clearNum($_GET['page']) : 1;

        !$pages['page'] && $pages['pagination']['page'] = 1;

        $this->data = $this->model->adminSearch($text, $table, $pages);

        $this->pagination = $this->model->getPagination();

        $this->template = ADMIN_TEMPLATE . 'show';

        $h1 = 'Результаты поиска по запросу ' . $text;

        return compact('h1');

    }

}