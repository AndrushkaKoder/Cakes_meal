<?php

namespace core\admin\helpers;

use core\base\settings\Settings;

trait ForegnHelper
{

    protected $foreignData;

    protected function createForeignData($settings = false){

        if(!$settings) $settings = Settings::instance();

        $rootItems = $settings::get('rootItems');

        $keys = $this->model->showForeignKeys($this->table);

        if($keys){

            foreach ($keys as $item){

                $this->createForeignProperty($item, $rootItems, $settings);

            }

        }elseif (!empty($this->columns['parent_id'])){

            $arr['COLUMN_NAME'] = 'parent_id';
            $arr['REFERENCED_COLUMN_NAME'] = $this->columns['id_row'];
            $arr['REFERENCED_TABLE_NAME'] = $this->table;

            $this->createForeignProperty($arr, $rootItems, $settings);
        }

        return;

    }

    protected function createForeignProperty($arr, $rootItems, $settings){

        if((!empty($rootItems['tables']) && in_array($this->table, $rootItems['tables'])) ||
            !empty($rootItems['tables'][$this->table]) ||
            $this->table === $arr['REFERENCED_TABLE_NAME']){

            $setRoot = true;

            if(!empty($rootItems['tables'][$this->table])){

                if(!in_array($arr['COLUMN_NAME'], (array)$rootItems['tables'][$this->table])){

                    $setRoot = false;

                }

            }

            if((!empty($this->userData['ROOT']) || !isset(Settings::get('tablesUserRootLevel')[$this->table])) && $setRoot){

                $this->foreignData[$arr['COLUMN_NAME']][0]['id'] = 'NULL';
                $this->foreignData[$arr['COLUMN_NAME']][0]['name'] = $rootItems['name'];

            }

        }

        $orderData = $this->createOrderData($arr['REFERENCED_TABLE_NAME']);

        $where = [];

        if(!empty($this->data)){

            if($arr['REFERENCED_TABLE_NAME'] === $this->table){
                $where['!' . $this->columns['id_row']] = $this->data[$this->columns['id_row']];
            }

        }

        $foreign = $this->model->get($arr['REFERENCED_TABLE_NAME'], [
            'fields' => [$arr['REFERENCED_COLUMN_NAME'] . ' as id', $orderData['name'], $orderData['parent_id']],
            'where' => $where,
            'order' => $orderData['order'],
            'join' => $orderData['join']
        ]);

        if($foreign){

            if(!empty($this->data) && $arr['REFERENCED_TABLE_NAME'] === $this->table && $orderData['parent_id'] === 'parent_id')
                $this->clearParents($foreign);

            $onlyRootParents = $settings::get('deepLevel');

            if(isset($onlyRootParents[$this->table][$arr['COLUMN_NAME']]) && $onlyRootParents[$this->table][$arr['COLUMN_NAME']] === 0){

                $this->onlyRootParents($foreign);

            }

            if(!empty($this->foreignData[$arr['COLUMN_NAME']]) || !empty($orderData['join'])){

                if(empty($this->foreignData[$arr['COLUMN_NAME']])){

                    $this->foreignData[$arr['COLUMN_NAME']] = [];

                }

                foreach ($foreign as $value){

                    if(!empty($value['foreignName']) && !empty($value['name'])){

                        $value['name'] = $value['foreignName'] . '->' . $value['name'];

                    }

                    $this->foreignData[$arr['COLUMN_NAME']][] = $value;

                }

                $this->foreignData[$arr['COLUMN_NAME']] = $this->recursiveArr($this->foreignData[$arr['COLUMN_NAME']]);

            }else{

                $this->foreignData[$arr['COLUMN_NAME']] = $this->recursiveArr($foreign);

            }

        }
    }

    protected function createOrderData($table){

        $columns = $this->model->showColumns($table);

        if(!$columns) return false;

        $name = '';
        $order_name = '';

        $keysName = [];

        if($columns['name']){

            $order_name = $name = 'name';

        }else{

            foreach ($columns as $key => $value){

                if(strpos($key, 'name') !== false){
                    $keysName[] = $order_name = $key;
                    $name = $key . ' as name';
                    break;
                }
            }

            if(!$name) $name = $columns['id_row'] . ' as name';

        }

        $fields = [];

        $fields[] = $name;

        foreach ($columns as $key => $item){

            if($key === 'id_row' || $key === 'multi_id_row' || in_array($key, $keysName)) continue;

            if($key === $columns['id_row']) $fields[] = $key . ' as id';
            else $fields[] = $key;

        }

        $parent_id = '';

        $order = [];

        $join = null;

        if(!empty($columns['parent_id'])){

            $parent_id = 'parent_id';

            $foreign = $this->model->showForeignKeys($table, 'parent_id');

            if (!empty($foreign[0]['REFERENCED_TABLE_NAME']) && $foreign[0]['REFERENCED_TABLE_NAME'] !== $table){

                $foreignColumns = $this->model->showColumns($foreign[0]['REFERENCED_TABLE_NAME']);

                if($foreignColumns['name']){

                    $foreignName = 'name as foreignName';

                }else{

                    foreach ($foreignColumns as $key => $value){

                        if(strpos($key, 'name') !== false){

                            $foreignName = $key . ' as foreignName';

                            break;

                        }
                    }

                }

                if(!empty($foreignName)){

                    $join = [$foreign[0]['REFERENCED_TABLE_NAME'] => [
                        'fields' => [$foreignName],
                        'on' => [$foreign[0]['COLUMN_NAME'] => $foreign[0]['REFERENCED_COLUMN_NAME']]
                    ]];

                }

            }

            $order[] = 'parent_id';

        }

        if(!empty($columns['menu_position'])){

            $order[] = 'menu_position';

        }else{

            $order[] = $order_name ?: $columns['id_row'];

        }

        return compact('name', 'parent_id', 'order', 'columns', 'fields', 'join');

    }

    protected function createManyToMany($settings = false){

        if(!$settings) $settings = $this->settings ?: Settings::instance();

        $manyToMany = $settings::get('manyToMany');

        $blocks = $settings::get('blockNeedle');

        if($manyToMany){

            foreach($manyToMany as $mTable => $tables){

                $targetKey = array_search($this->table, $tables);

                if($targetKey !== false){

                    $otherKey = $targetKey ? 0 : 1;

                    $checkboxList = $settings::get('templateArr')['checkboxlist'];

                    if(!$checkboxList) continue;

                    $search = $tables[$otherKey];

                    $existsInTemplate = (bool)array_filter($checkboxList, function ($v) use($search){

                        return (!is_array($v) && $v == $search) || (is_array($v) && in_array($search, $v));

                    }, ARRAY_FILTER_USE_BOTH);

                    if(!$existsInTemplate) continue;

                    $checkBoxArr = array_filter($checkboxList, function ($v, $k) use($search){

                        return ((!is_array($v) && $v == $search) || (is_array($v) && in_array($search, $v))) && !is_numeric($k);

                    }, ARRAY_FILTER_USE_BOTH);

                    if($checkBoxArr && !isset($checkBoxArr[$tables[$targetKey]])) continue;

                    if(!empty($this->translate[$tables[$otherKey]])){

                        if($settings::get('projectTables')[$tables[$otherKey]])
                            $this->translate[$tables[$otherKey]] = [$settings::get('projectTables')[$tables[$otherKey]]['name']];

                    }

                    $orderData = $this->createOrderData($tables[$otherKey]);

                    if($orderData){

                        if($blocks){

                            $insert = false;

                            foreach($blocks as $key => $item){

                                if(in_array($tables[$otherKey], $item)){
                                    $this->blocks[$key][] = $tables[$otherKey];
                                    $insert = true;
                                    break;
                                }
                            }

                            if(!$insert){
                                $this->blocks[array_keys($this->blocks)[0]][] = $tables[$otherKey];
                            }

                        }


                        $foreign = [];

                        $mTableColumns = $this->model->showColumns($mTable);

                        $otherRow = $tables[$otherKey] . '_' . $orderData['columns']['id_row'];

                        foreach ($mTableColumns as $col => $item){

                            if($col !== 'id_row' && $col !== 'multi_id_row' &&
                                $col !== $this->table . '_' . $this->columns['id_row'] &&
                                stripos($col, $otherRow) !== false){

                                $otherRow = $col;

                                break;

                            }

                        }

                        if($this->data){

                            $res = $this->model->get($mTable, [
                                'where' => [$this->table . '_' . $this->columns['id_row'] => $this->data[$this->columns['id_row']]]
                            ]);

                            if($res){

                                foreach ($res as $item){

                                    $value = isset($item[$this->table . '_value']) ? $item[$this->table . '_value'] : false;

                                    $foreign[$item[$otherRow]] = $value;

                                }

                            }

                        }

                        if(isset($tables['type'])){

                            $data = $this->model->get($tables[$otherKey], [
                                'fields' => $orderData['fields'],
                                'order' => $orderData['order']
                            ]);

                            if($data){

                                if(empty($this->userData['ROOT']) &&
                                    !empty($this->userData['credentials'][$tables[$otherKey]]['show']['properties']) &&
                                    in_array('data_creators', $this->model->showTables())){

                                    $ids = [];

                                    $resIds = $this->model->get('data_creators', [
                                        'fields' => ['data_id'],
                                        'where' => ['creator_id' => $this->userData['id'], 'table' => $tables[$otherKey]]
                                    ]);

                                    if($resIds){

                                        $ids = array_column($resIds, 'data_id');

                                    }

                                }

                                $resData = $this->recursiveArr($data, 1);

                                if($resData){

                                    $data = [];

                                    foreach ($resData as $item){

                                        if(!empty($item['recursive_name'])){

                                            $item['name'] = $item['recursive_name'];

                                        }

                                        $data[] = $item;

                                        if(!empty($item['sub'])){

                                            foreach ($item['sub'] as $value){

                                                if(!empty($value['recursive_name'])){

                                                    $value['name'] = $value['recursive_name'];

                                                }

                                                $data[] = $value;

                                            }

                                        }

                                    }

                                }

                                $this->foreignData[$tables[$otherKey]][$tables[$otherKey]]['name'] = 'Выбрать';

                                foreach ($data as $item){

                                    if(isset($ids)){

                                        if(!in_array($item[$orderData['columns']['id_row']], $ids)){

                                            continue;

                                        }

                                    }

                                    if(isset($mTableColumns[$this->table . '_value'])) $item[$this->table . '_value'] = true;

                                    if($tables['type'] === 'root' && $orderData['parent_id']){

                                        if($item[$orderData['parent_id']] === null)
                                            $this->foreignData[$tables[$otherKey]][$tables[$otherKey]]['sub'][] = $item;

                                    }elseif ($tables['type'] === 'child' && $orderData['parent_id']){

                                        if($item[$orderData['parent_id']] !== null)
                                            $this->foreignData[$tables[$otherKey]][$tables[$otherKey]]['sub'][] = $item;

                                    }else{

                                        $this->foreignData[$tables[$otherKey]][$tables[$otherKey]]['sub'][] = $item;

                                    }

                                    if(isset($foreign[$item['id']]))
                                        $this->data[$tables[$otherKey]][$tables[$otherKey]][$item['id']] = $foreign[$item['id']];
                                }
                            }

                        }elseif($orderData['parent_id']){

                            $parent = $tables[$otherKey];

                            $keys = $this->model->showForeignKeys($tables[$otherKey]);

                            if($keys){

                                foreach ($keys as $item){

                                    if($item['COLUMN_NAME'] === 'parent_id'){

                                        $parent = $item['REFERENCED_TABLE_NAME'];

                                        break;

                                    }

                                }

                            }


                            if($parent === $tables[$otherKey]){

                                $data = $this->model->get($tables[$otherKey], [
                                    'fields' => $orderData['fields'],
                                    'order' => $orderData['order']
                                ]);

                                if($data){

                                    $this->foreignData[$tables[$otherKey]] = $this->recursiveArr($data, 1);

                                    foreach ($this->foreignData[$tables[$otherKey]] as $key => $item){

                                        if(isset($foreign[$item['id']])) $this->data[$tables[$otherKey]][$item['id']][$item['id']] = $item['id'];

                                        if(!empty($item['sub'])){

                                            foreach ($item['sub'] as $k => $v){

                                                if(isset($mTableColumns[$this->table . '_value'])) $this->foreignData[$tables[$otherKey]][$key]['sub'][$k][$this->table . '_value'] = true;

                                                if(isset($foreign[$v['id']])) $this->data[$tables[$otherKey]][$item['id']][$v['id']] = $foreign[$v['id']];

                                            }

                                        }

                                    }

                                }

                            }else{

                                $parentOrderData = $this->createOrderData($parent);

                                $data = $this->model->get($parent, [
                                    'fields' => [$parentOrderData['name']],
                                    'join' => [
                                        $tables[$otherKey] => [
                                            'fields' => $orderData['fields'],
                                            'on' => [$parentOrderData['columns']['id_row'], 'parent_id']
                                        ]
                                    ],
                                    'join_credentials_table' => $tables[$otherKey],
                                    'join_structure' => true
                                ]);

                                if($data){

                                    foreach ($data as $key => $item){

                                        if(isset($item['join'][$tables[$otherKey]]) && $item['join'][$tables[$otherKey]]){

//                                            if(isset($mTableColumns[$this->table . '_value'])){
//
//                                                foreach($item['join'][$tables[$otherKey]] as $k => $v)
//                                                    $item['join'][$tables[$otherKey]][$k][$this->table . '_value'] = true;
//
//                                            }
                                            foreach ($item['join'][$tables[$otherKey]] as $k => $v){

                                                if(isset($mTableColumns[$this->table . '_value'])){

                                                    $v[$this->table . '_value'] = true;

                                                    $item['join'][$tables[$otherKey]][$k][$this->table . '_value'] = true;

                                                }

                                                if(isset($foreign[$v['id']])) $this->data[$tables[$otherKey]][$key][$v['id']] = $foreign[$v['id']];

                                            }

                                            $this->foreignData[$tables[$otherKey]][$key]['name'] = $item['name'];
                                            $this->foreignData[$tables[$otherKey]][$key]['sub'] = $item['join'][$tables[$otherKey]];



                                        }

                                    }

                                }

                            }

                        }else{

                            $data = $this->model->get($tables[$otherKey], [
                                'fields' => $orderData['fields'],
                                'order' => $orderData['order']
                            ]);

                            if($data){

                                $this->foreignData[$tables[$otherKey]][$tables[$otherKey]]['name'] = 'Выбрать';

                                foreach ($data as $item){

                                    if(isset($mTableColumns[$this->table . '_value']))
                                        $item[$this->table . '_value'] = true;

                                    $this->foreignData[$tables[$otherKey]][$tables[$otherKey]]['sub'][] = $item;

                                    if(isset($foreign[$item['id']])) $this->data[$tables[$otherKey]][$tables[$otherKey]][$item['id']] = $foreign[$item['id']];

                                }

                            }

                        }

                        if($this->table === $tables[$otherKey] && !empty($this->foreignData[$tables[$otherKey]]) && $this->data){

                            foreach ($this->foreignData[$tables[$otherKey]] as $key => $item){

                                if(!empty($item['sub'])){

                                    foreach ($item['sub'] as $k => $v){

                                        if($v[$this->columns['id_row']] === $this->data[$this->columns['id_row']]){

                                            unset($this->foreignData[$tables[$otherKey]][$key]['sub'][$k]);

                                        }

                                    }

                                }

                            }

                        }

                    }

                }

            }

        }

    }

    protected function checkManyToMany($settings = false){

        if(!$settings) $settings = $this->settings ?: Settings::instance();

        $manyToMany = $settings::get('manyToMany');

        if($manyToMany){

            foreach($manyToMany as $mTable => $tables){

                $targetKey = array_search($this->table, $tables);

                if($targetKey !== false){

                    $otherKey = $targetKey ? 0 : 1;

                    $checkboxList = $settings::get('templateArr')['checkboxlist'];

                    if(!$checkboxList) continue;

                    $search = $tables[$otherKey];

                    $existsInTemplate = (bool)array_filter($checkboxList, function ($v) use($search){

                        return (!is_array($v) && $v == $search) || (is_array($v) && in_array($search, $v));

                    }, ARRAY_FILTER_USE_BOTH);

                    if(!$existsInTemplate) continue;

                    $checkBoxArr = array_filter($checkboxList, function ($v, $k) use($search){

                        return ((!is_array($v) && $v == $search) || (is_array($v) && in_array($search, $v))) && !is_numeric($k);

                    }, ARRAY_FILTER_USE_BOTH);

                    if($checkBoxArr && !isset($checkBoxArr[$tables[$targetKey]])) continue;

                    $columns = $this->model->showColumns($tables[$otherKey]);

                    $targetRow = $this->table . '_' .$this->columns['id_row'];

                    $otherRow = $tables[$otherKey] . '_' . $columns['id_row'];

                    $mTableColumns = $this->model->showColumns($mTable);

                    foreach ($mTableColumns as $col => $item){

                        if($col !== 'id_row' && $col !== 'multi_id_row' &&
                            $col !== $this->table . '_' . $this->columns['id_row'] &&
                            stripos($col, $otherRow) !== false){

                            $otherRow = $col;

                            break;

                        }

                    }

                    if(empty($this->userData['ROOT']) &&
                        !empty($this->userData['credentials'][$tables[$otherKey]]['show']['properties']) &&
                        in_array('data_creators', $this->model->showTables())){

                        $resIds = $this->model->get($tables[$otherKey], [
                            'fields' => [$columns['id_row']]
                        ]);

                        if($resIds){

                            $resIds = array_column($resIds, $columns['id_row']);

                        }

                    }

                    $this->model->delete($mTable, [
                        'where' => [$targetRow => $_POST[$this->columns['id_row']]]
                    ]);

                    if(!empty($_POST[$tables[$otherKey]])){

                        $insertArr = [];
                        $i = 0;

                        foreach($_POST[$tables[$otherKey]] as $value){

                            foreach ($value as $item){

                                if(!empty($item['id'])){

                                    if(!empty($resIds) && !in_array($item['id'], $resIds)){

                                        continue;

                                    }

                                    $insertArr[$i][$targetRow] = $_POST[$this->columns['id_row']];
                                    $insertArr[$i][$otherRow] = $item['id'];

                                    if(!empty($item[$this->table . '_value']))
                                        $insertArr[$i][$this->table . '_value'] = $item[$this->table . '_value'];

                                    $i++;

                                }

                            }

                        }

                        if($insertArr){

                            $this->model->add($mTable, [
                                'fields' => $insertArr
                            ]);

                        }

                    }

                }

            }

        }

    }

}