<?php

namespace core\admin\helpers;

use core\base\controller\BaseMethods;
use core\base\exceptions\RouteException;
use core\base\settings\Settings;

trait DataCreatorsModelHelper
{

    use BaseMethods;

    protected function checkDataCreators(){

        if(!in_array('data_creators', $this->showTables())){

            $query = "create table data_creators
                            (
                                creator_id    int          not null,
                                data_id       int           not null,
                                `table`       varchar(190) not null,
                                date_creation datetime     null,
                                date_editing  datetime     null,
                                date_deleting datetime     null,
                                modifier_id   int          null,
                                deleter_id    int          null,
                                creator_before_deleting_id    int          null,
                                row_data      longtext          null, 
                                constraint data_creators_pk
                                    primary key (creator_id, data_id, `table`)
                            );";

            $res = $this->query($query, 'c') && !empty($this->userData);

            if($res){

                $this->projectTables[] = 'data_creators';

                return true;

            }

            return false;

        }

        return !empty($this->userData);

    }

    public function add($table, $set = [], $callback = ''){

        $this->checkForeign($table);

        $res = parent::add($table, $set, $callback = '');

        if($res && !empty($set['return_id']) && !empty(Settings::get('projectTables')[$table])){

            if($this->checkDataCreators()){

                parent::add('data_creators', [
                    'fields' => [
                        'creator_id' => $this->userData['id'],
                        'data_id' => $res,
                        'table' => $table,
                        'date_creation' => 'NOW()'
                    ]
                ]);

            }

        }

        return $res;

    }

    protected function checkForeign($table){

        if(empty($this->userData['ROOT']) &&
            $this->checkDataCreators() &&
            !empty(Settings::get('projectTables')[$table])){

            $foreign = $this->showForeignKeys($table);

            $checkParentId = true;

            if(array_key_exists('parent_id', $_POST)){

                $checkParentId = false;

            }

            if($foreign){

                foreach ($foreign as $item){

                    $blocked = $this->getForeignIds($item['REFERENCED_TABLE_NAME'], $item['COLUMN_NAME']);

                    if($item['COLUMN_NAME'] === 'parent_id'){

                        $checkParentId = true;

                    }

                    if($blocked){

                        throw new RouteException('Попытка сохранения некорректного идентификатора внешнего ключа ' .
                            $item['COLUMN_NAME'] . ' в таблице ' . $table . ' пользователем - ' . $this->userData['id'] .
                            ' идентификатор ключа ' . ($_POST[$item['COLUMN_NAME']] ?? null), 3);

                    }

                }

            }

            if(array_key_exists('parent_id', $_POST) && !$checkParentId){

                if($this->getForeignIds($table, 'parent_id')){

                    throw new RouteException('Попытка сохранения некорректного идентификатора внешнего ключа ' .
                        'parent_id в таблице ' . $table . ' пользователем - ' . $this->userData['id'] .
                        ' идентификатор ключа  - parent_id', 3);

                }

            }



        }

    }

    protected function getForeignIds($foreignTable, $column){

        $blocked = false;

        if(empty($this->userData['credentials'][$foreignTable]['show'])){

            $blocked = true;

        }else{

            $tablesUserRootLevel = Settings::get('tablesUserRootLevel');

            $postValue = (isset($_POST[$column]) && is_string($_POST[$column]) && strtolower($_POST[$column]) === 'null') ? null : ($_POST[$column] ?? null);

            if(!empty($tablesUserRootLevel[$foreignTable]) && empty($postValue)){

                $blocked = true;

            }else{

                if(!empty($this->userData['credentials'][$foreignTable]['show']['properties'])){

                    $resIds = parent::get('data_creators', [
                        'fields' => ['data_id'],
                        'where' => [
                            'table' => $foreignTable,
                            'creator_id' => $this->userData['id']
                        ]
                    ]);

                    if($resIds){

                        $resIds = array_column($resIds, 'data_id');

                    }else{

                        $resIds = [];

                    }

                    if(isset($tablesUserRootLevel[$foreignTable])){

                        $ids = $this->getRootLevelIds($foreignTable, $tablesUserRootLevel[$foreignTable]);

                        $resIds && $ids = array_unique(array_merge($ids, $resIds));

                    }else{

                        if($resIds){

                            $ids = $this->getParents($resIds, $foreignTable);

                        }

                    }

                }elseif(isset($tablesUserRootLevel[$foreignTable])){

                    $ids = $this->getRootLevelIds($foreignTable, $tablesUserRootLevel[$foreignTable]);

                }

                if(!empty($ids) && !in_array($postValue, $ids)){

                    $blocked = true;

                }

            }


        }

        return $blocked;

    }

    public function edit($table, $set = []){

        $this->checkForeign($table);

        if($this->checkDataCreators() && empty($this->userData['ROOT']) &&
            !empty(Settings::get('projectTables')[$table]) &&
                !empty($this->userData['credentials'][$table]['edit']['properties'])){

            if(!empty($set['where'])){

                $columns = $this->showColumns($table);

                if(empty($set['where'][$columns['id_row']]) || !empty($set['all_rows'])){

                    $dataCreators = parent::get('data_creators', [
                        'fields' => ['data_id'],
                        'where' => ['creator_id' => $this->userData['id'], 'table' => $table],
                    ]);

                    if($dataCreators){

                        $set['where'][$columns['id_row']] = array_column($dataCreators, 'data_id');

                    }

                }

            }

        }

        $res = parent::edit($table, $set);

        if($res && $this->checkDataCreators() &&
            empty($set['all_rows']) && !empty(Settings::get('projectTables')[$table])){

            $fields = (!empty($set['fields']) && is_array($set['fields'])) ? $set['fields'] : $_POST;

            $columns = $this->showColumns($table);

            $id = !empty($set['where'][$columns['id_row']]) ? $set['where'][$columns['id_row']] :
                (!empty($fields[$columns['id_row']]) ? $fields[$columns['id_row']] : null);

            if($id){

                $dataCreators = parent::get('data_creators', [
                    'where' => ['creator_id' => $this->userData['id'], 'data_id' => $id, 'table' => $table],
                    'single' => true
                ]);

                if($dataCreators){

                    parent::edit('data_creators', [
                        'fields' => [
                            'modifier_id' => $this->userData['id'],
                            'date_editing' => 'NOW()',
                        ],
                        'where' => ['creator_id' => $this->userData['id'], 'data_id' => $id, 'table' => $table],
                    ]);

                }

            }

        }

        return $res;

    }

    public function delete($table, $set = [])
    {

        if(!empty($set['where']) && empty($set['fields']) &&
            $this->userData && $this->checkDataCreators() &&
            !empty(Settings::get('projectTables')[$table])){

            $columns = $this->showColumns($table);

            if(!empty($set['where'][$columns['id_row']])){

                $dataCreators = parent::get('data_creators', [
                    'where' => ['creator_id' => $this->userData['id'], 'data_id' => $set['where'][$columns['id_row']], 'table' => $table],
                    'single' => true
                ]);

                if($dataCreators){

                    parent::edit('data_creators', [
                        'fields' => [
                            'deleter_id' => $this->userData['id'],
                            'creator_before_deleting_id' => $dataCreators['creator_id'],
                            'creator_id' => 0,
                            'date_deleting' => 'NOW()',
                            'row_data' => parent::get($table, [
                                'where' => [$columns['id_row'] => $set['where'][$columns['id_row']]],
                                'single' => true,
                            ])
                        ],
                        'where' => ['creator_id' => $this->userData['id'], 'data_id' => $set['where'][$columns['id_row']], 'table' => $table],
                    ]);

                }

            }

        }

        return parent::delete($table, $set);

    }

    public function get($table, $set = []){

        $realTable = $table;

        if(empty($set['no_check_credentials']) && empty($this->userData['ROOT']) &&
            !empty(Settings::get('projectTables')[$table])){

            $setJoinCredentials = false;

            if(!empty($set['join_credentials_table']) && !empty($set['join'])){

                if(!isset($set['join'][$set['join_credentials_table']])){

                    foreach ($set['join'] as $key => $item){

                        if(stripos($key, $set['join_credentials_table'] . ' ') === 0){

                            $table = $setJoinCredentials = $key;

                            break;

                        }

                    }

                }else{

                    $table = $setJoinCredentials = $set['join_credentials_table'];

                }

                if(preg_match('/[^\s]\s+[^\s]/', $table)){

                    $table = preg_split('/\s+/', $table, 2, PREG_SPLIT_NO_EMPTY)[0];

                }

            }

            if(empty($this->userData['credentials'][$table]['show'])){

                return false;

            }

            if(!empty($this->userData['credentials'][$table]['show']['properties'])){

                if(!$this->checkDataCreators()){

                    return false;

                }

                $tableData = parent::get('data_creators', [
                    'fields' => ['data_id'],
                    'where' => ['creator_id' => $this->userData['id'], 'table' => $table]
                ]);

                $tablesUserRootLevel = Settings::get('tablesUserRootLevel');

                $columns = $this->showColumns($table);

                $foreign = $this->showForeignKeys($table, 'parent_id');

                if(!$tableData){

                    if(empty($columns['parent_id'])){

                        return false;

                    }else{

                        if(empty($foreign) || $foreign[0]['REFERENCED_TABLE_NAME'] !== $table || !isset($tablesUserRootLevel[$table])){

                            return false;

                        }

                    }

                    if(!($ids = $this->getRootLevelIds($table, $tablesUserRootLevel[$table]))){

                        return false;

                    }

                }else{

                    $ids = array_column($tableData, 'data_id');

                    if(!empty($columns['parent_id']) && (empty($foreign) || $foreign[0]['REFERENCED_TABLE_NAME'] === $table)){

                        $ids = $this->getParents($ids, $table);

                        if(isset($tablesUserRootLevel[$table]) && ($rootIds = $this->getRootLevelIds($table, $tablesUserRootLevel[$table]))){

                            $ids = array_unique(array_merge($ids, $rootIds));

                        }

                    }

                }

                if(!empty($ids)){

                    if(!empty($setJoinCredentials) && !empty($set['join'][$setJoinCredentials])){

                        if(empty($set['join'][$setJoinCredentials]['where'])){

                            $set['join'][$setJoinCredentials]['where'] = [];

                            $set['join'][$setJoinCredentials]['where'][$columns['id_row']] = $ids;

                        }else{

                            $alternativeIdRow = $columns['id_row'];

                            while (!empty($set['join'][$setJoinCredentials]['where'][$alternativeIdRow])){

                                $alternativeIdRow = ' ' . $alternativeIdRow;

                            }

                            $set['join'][$setJoinCredentials]['where'][$alternativeIdRow] = $ids;

                        }

                    }else{

                        if(empty($set['where'])){

                            $set['where'] = [];

                            $set['where'][$columns['id_row']] = $ids;

                        }else{

                            if(is_array($set['where'])){

                                $alternativeIdRow = $columns['id_row'];

                                while (!empty($set['where'][$alternativeIdRow])){

                                    $alternativeIdRow = ' ' . $alternativeIdRow;

                                }

                                $set['where'][$alternativeIdRow] = $ids;

                            }elseif(is_string($set['where'])){

                                if(($key = array_search(null, $ids)) !== false){

                                    unset($ids[$key]);

                                }

                                $set['where'] .= ' AND ' . $columns['id_row'] . ' IN(' . (implode(',', $ids)) . ')';

                            }


                        }

                    }

                }

            }

        }

        return parent::get($realTable, $set);

    }

    protected function getRootLevelIds($table, $rootLevel){

        $columns = $this->showColumns($table);

        $ids = [];

        if(!empty($columns['parent_id'])){

            $data = parent::get($table, [
                'fields' => [$columns['id_row'], 'parent_id']
            ]);

            if(!$data){

                return false;

            }

            $data = $this->recursiveArr($data, 1);

            foreach ($data as $item){

                $ids[] = $item[$columns['id_row']];

                if($rootLevel && !empty($item['sub'])){

                    foreach ($item['sub'] as $value){

                        if($value['depth_level'] <= $rootLevel){

                            $ids[] = $value[$columns['id_row']];

                        }

                    }

                }

            }

        }

        return $ids;

    }

}