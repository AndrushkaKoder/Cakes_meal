<?php

namespace web\user\controllers;

use core\exceptions\RouteException;

class CatalogController extends BaseUser
{


    protected function actionInput(){

        $where = [
            'visible' => 1
        ];

        $single = false;


        if(!empty($this->parameters[0])){
            $where['alias'] = \AppH::clearStr($this->parameters[0]);
            $single = true;
        }

        $data = $this->model->get('catalog', [
            'where' => $where,
            'join_structure' => true
        ]);

        if(!$data){
            throw new RouteException('Отсутствуют разделы в каталоге');
        }

        $catalogFilters = $catalogPrices = null;

        $goods = $this->getGoods([
            'where' => [
                'visible' => 1,
                'parent_id' => array_column($data, 'id')
            ]
        ], $catalogFilters, $catalogPrices);

        if($goods){

            foreach ($goods as $item){

                $data[$item['parent_id']]['join']['goods'][$item['id']] = $item;

            }
        }

        $h1 = $single ? $data[key($data)]['name'] : 'Каталог';

        return compact('data', 'h1', 'catalogPrices', 'catalogFilters');

    }



}