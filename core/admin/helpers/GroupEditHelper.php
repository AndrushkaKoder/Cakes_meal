<?php

namespace webQAdmin\helpers;

use webQAdminSettings\Settings;

trait GroupEditHelper
{

    protected function checkGroupEdit($oldData){

        $groupEdit = Settings::get('groupEditRows');

        if($groupEdit){

            foreach ($groupEdit as $row => $values){

                if((empty($values['tables']) || in_array($this->table, $values['tables'])) && $values['connectingRow']){

                    foreach ((array)$values['connectingRow'] as $connectingRow){

                        $this->groupEdit($oldData, $row, $connectingRow);

                    }

                }

            }

        }

    }

    protected function groupEdit($oldData, $row = 'visible', $connectingRow = 'parent_id', $id = false, $table = false, $value = false){

        $value === false && $value = $_POST[$row];

        !$table && $table = $this->table;

        $columns = $this->columns ?: $this->model->showColumns($table);

        !$id && $id = $_POST[$columns['id_row']];

        if($id && !empty($columns[$row]) && !empty($columns[$connectingRow]) && $oldData){

            if($oldData[$row] !== $value){

                $this->setForeignGroupEdit($id, $table, $value, $row, $connectingRow);

            }

        }

    }

    protected function setForeignGroupEdit($ids, $table, $value, $row = 'visible', $connectingRow = 'parent_id', $keys = false){

        $changedTables = [];

        if($ids && $table){

            $ids = (array)$ids;

            $keys === false && $keys = $this->model->showForeignKeys();

            if($keys){

                foreach ($keys as $item){

                    if($item['COLUMN_NAME'] === $connectingRow && $item['REFERENCED_TABLE_NAME'] === $table){

                        if($item['TABLE_NAME'] !== $table){

                            $foreignColumns = $this->model->showColumns($item['TABLE_NAME']);

                            $res = $this->model->get($item['TABLE_NAME'], [
                                'fields' => [$foreignColumns['id_row']],
                                'where' => [$connectingRow => $ids]
                            ]);

                            if($res){

                                $resIds = [];

                                foreach ($res as $r){

                                    $resIds[] = $r[$foreignColumns['id_row']];

                                }

                                if(isset($foreignColumns[$row])){

                                    $this->model->edit($item['TABLE_NAME'], [
                                        'fields' => [$row => $value],
                                        'where' => [$foreignColumns['id_row'] => $resIds]
                                    ]);

                                    if(in_array('cached_tables', $this->model->showTables())){

                                        $this->model->edit('cached_tables', [
                                            'fields' => ['date' => 'NOW()'],
                                            'where' => ['name' => $item['TABLE_NAME']]
                                        ]);

                                    }

                                }

                                $this->setForeignGroupEdit($resIds, $item['TABLE_NAME'], $value, $row, $connectingRow, $keys);

                            }

                        }else{

                            $ids = array_merge($ids, $this->getChildData($ids, $table, $connectingRow));

                            $columns = $this->model->showColumns($table);

                            if($ids){

                                if(!empty($columns[$row])){

                                    $this->model->edit($table, [
                                        'fields' => [$row => $value],
                                        'where' => [$columns['id_row'] => $ids]
                                    ]);

                                    if(in_array('cached_tables', $this->model->showTables())){

                                        $this->model->edit('cached_tables', [
                                            'fields' => ['date' => 'NOW()'],
                                            'where' => ['name' => $table]
                                        ]);

                                    }

                                }

                            }

                        }


                    }

                }

            }

        }

    }

    protected function getChildData($id, $table = false, $connectingRow = 'parent_id', $columns = false){

        $result = [];

        !$table && $table = $this->table;

        !$columns && $columns = $this->columns ?: $this->model->showColumns($table);

        if($id){

            $res = $this->model->get($table, [
                'fields' => [$columns['id_row']],
                'where' => [$connectingRow => $id]
            ]);

            if($res){

                foreach ($res as $item){

                    $result[] = $item[$columns['id_row']];

                }

                $result = array_merge($result, $this->getChildData($result, $table, $connectingRow, $columns));

            }

        }

        return $result;

    }

}