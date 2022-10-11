<?php

namespace web\user\controllers;

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
            'join' => [
                'goods' => [
                    'where' => [
                        'visible' => 1
                    ],
                    'on' => [
                        'id' => 'parent_id'
                    ]
                ]
            ],
            'join_structure' => true,
//            'return_query' => true,
//            'single' => true
        ]);

        $h1 = $single ? $data[key($data)]['name'] : 'Каталог';
        return compact('data', 'h1');

    }



}