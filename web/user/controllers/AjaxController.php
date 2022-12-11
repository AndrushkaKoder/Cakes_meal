<?php

namespace webQApplication\controllers;

use web\user\controllers\SearchController;

class AjaxController extends BaseUser
{
    public $ajaxData = [];

    protected function actionInput(){

        $this->skipRenderingTemplates = true;

        if(!empty($_REQUEST)){
            $this->ajaxData = $_REQUEST;
        }

        if(!empty($_GET['ajax']) && $_GET['ajax'] === 'add_to_cart'){

            return $this->addToCart();


        } else if ($this->ajaxData['ajax'] === 'site_search') {

            return $this->userSearch();
        }

    }

    protected function userSearch(){

        $search = new SearchController();

        $res = $search->searchData();

        if(!empty($res['data'])){

            foreach ($res['data'] as $key => $item){

                $res['data'][$key]['alias'] = $this->alias(['product' => $item['alias']]);

            }

            return $res['data'];

        }

        return [];

    }



}