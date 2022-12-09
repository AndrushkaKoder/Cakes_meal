<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 07.03.2019
 * Time: 13:13
 */

namespace webQAdmin\expansion;

use webQAdminSettings\Settings;

class MetadataExpansion extends Expansion
{

    public function expansion($args = [], $obj = false){

        $no_add = true;
        $no_delete = true;

        parent::expansion($args, $obj);

        $this->checkTableStructure();

        if($this->className === 'Show'){

            $project_tables = Settings::get('projectTables');

            $exceptionTables = Settings::get('menuException');

            $meta_data = [];

            $delete_meta = [];

            $res = $this->model->get('metadata', [
                'fields' => ['table_name']
            ]);

            if($res){

                foreach ($res as $item){

                    if(empty($project_tables[$item['table_name']])){

                        $delete_meta[] = $item['table_name'];

                        continue;
                    }

                    $meta_data[] = $item['table_name'];
                }
            }

            if($project_tables){

                $new_meta = [];

                foreach($project_tables as $table_name => $value){

                    if(in_array($table_name, $exceptionTables)) continue;

                    $table_data = $this->model->showColumns($table_name);

                    if(!empty($table_data['description'])){

                        if(!in_array($table_name, $meta_data)){
                            $name = $project_tables[$table_name]['name'] ?: $table_name;
                            $new_meta[] = ['name' => $name, 'table_name' => $table_name];
                        }

                    }

                }

                if($delete_meta){
                    $this->model->delete('metadata', [
                        'where' => ['table_name' => $delete_meta],
                    ]);
                }

                if($new_meta){

                    $this->model->add('metadata', [
                        'fields' => $new_meta
                    ]);

                    $redirect = preg_match('/\/metadata(\/|$)/', $_SERVER['HTTP_REFERER']) ? null :
                        \Wq::PATH() . Settings::get('routes')['admin']['alias'] . '/show/metadata';

                    \WqH::redirect($redirect);

                }

            }

        }elseif ($this->className === 'Add' || $this->className === 'Edit'){

            if(($key = array_search('table_name', $this->templateArr['text'])) !== false){

                unset($this->templateArr['text'][$key]);

            }

            if(!isset($this->templateArr['text_disabled']) || !is_array($this->templateArr['text_disabled'])){

                $this->templateArr['text_disabled'] = [];

            }

            if(!in_array('table_name', $this->templateArr['text_disabled'])){

                $this->templateArr['text_disabled'][] = 'table_name';

            }

            if(empty($this->translate['table_name'][0]) || $this->translate['table_name'][0] === 'table_name'){

                $this->translate['table_name'] = ['Название таблицы'];

            }

        }

        return compact('no_add', 'no_delete');

    }

    private function checkTableStructure(){

        if(!isset($this->model->showColumns('metadata')['table_name'])){

            $this->model->query('ALTER TABLE metadata add table_name varchar(255) null', 'u');

            $this->model->showColumns('metadata', true);

        }

    }

}