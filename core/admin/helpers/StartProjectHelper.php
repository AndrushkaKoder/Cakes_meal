<?php

namespace core\admin\helpers;

use core\base\exceptions\DbException;
use core\base\settings\Settings;

trait StartProjectHelper
{

    protected function checkExistingProjectTables(){

        $baseTableStructure = [
            'columns' => [
                'id' => [
                    'type' => 'int',
                    'Null' => 'not null',
                    'Default' => false,
                    'length' => false,
                    'other' => 'auto_increment'
                ],
                'name' => [
                    'type' => 'varchar',
                    'Null' => 'null',
                    'Default' => false,
                    'length' => 255
                ],
                'alias' => [
                    'type' => 'varchar',
                    'Null' => 'null',
                    'Default' => false,
                    'length' => 190
                ],
                'description' => [
                    'type' => 'varchar',
                    'Null' => 'null',
                    'Default' => false,
                    'length' => 400
                ],
                'keywords' => [
                    'type' => 'varchar',
                    'Null' => 'null',
                    'Default' => false,
                    'length' => 400
                ],
                'short_content' => [
                    'type' => 'text',
                    'Null' => 'null',
                    'Default' => false,
                    'length' => false
                ],
                'content' => [
                    'type' => 'text',
                    'Null' => 'null',
                    'Default' => false,
                    'length' => false
                ],
                'img' => [
                    'type' => 'varchar',
                    'Null' => 'null',
                    'Default' => false,
                    'length' => 255
                ],
                'gallery_img' => [
                    'type' => 'text',
                    'Null' => 'null',
                    'Default' => false,
                    'length' => false
                ],
                'visible' => [
                    'type' => 'tinyint',
                    'Null' => 'null',
                    'Default' => 1,
                    'length' => 1
                ],
                'menu_position' => [
                    'type' => 'int',
                    'Null' => 'null',
                    'Default' => 1,
                    'length' => false
                ],
            ],
            'dop_table_columns' => [
                'catalog' => [
                    'columns' => [
                        'parent_id' => [
                            'type' => 'int',
                            'Null' => 'null',
                            'Default' => false,
                            'length' => false
                        ]
                    ],
                    'keys' => [
                        'foreign' => [
                            'parent_id' => [
                                'references' => [
                                    'catalog' => 'id'
                                ],
                                'actions' => [
                                    'update' => 'null',
                                    'delete' => 'null'
                                ]
                            ]

                        ]
                    ]
                ],
                'product' => [
                    'columns' => [
                        'parent_id' => [
                            'type' => 'int',
                            'Null' => 'null',
                            'Default' => false,
                            'length' => false
                        ]
                    ],
                    'keys' => [
                        'foreign' => [
                            'parent_id' => [
                                'references' => [
                                    'catalog' => 'id'
                                ],
                                'actions' => [
                                    'update' => 'null',
                                    'delete' => 'null'
                                ]
                            ]

                        ]
                    ]
                ],
                'settings' => [
                    'columns' => [
                        'phone' => [
                            'type' => 'varchar',
                            'Null' => 'null',
                            'Default' => false,
                            'length' => 400
                        ],
                        'address' => [
                            'type' => 'varchar',
                            'Null' => 'null',
                            'Default' => false,
                            'length' => 400
                        ],
                        'email' => [
                            'type' => 'varchar',
                            'Null' => 'null',
                            'Default' => false,
                            'length' => 400
                        ],
                    ]
                ]
            ],

            'keys' => [
                'primary' => 'id',
            ],
            'indexes' => [
                'alias' => 'unique',
            ]
        ];

        try{

            $dbTables = $this->model->showTables();

        }catch (DbException $e){

            return;

        }

        if(!empty(Settings::get('projectTables'))){

            $createNewTable = false;

            foreach (Settings::get('projectTables') as $table => $values){

                $sql = '';

                if(!$dbTables || !in_array($table, $dbTables)){

                    $sql = $this->setSqlColumns($baseTableStructure['columns'], $sql);

                    if(!empty($baseTableStructure['dop_table_columns'][$table]['columns'])){

                        $sql = $this->setSqlColumns($baseTableStructure['dop_table_columns'][$table]['columns'], $sql);

                    }

                    if(!empty($baseTableStructure['keys'])){

                        $sql = $this->setSqlTableKeys($baseTableStructure['keys'], $table, $sql);

                    }

                    if(!empty($baseTableStructure['dop_table_columns'][$table]['keys'])){

                        $sql = $this->setSqlTableKeys($baseTableStructure['dop_table_columns'][$table]['keys'], $table, $sql);

                    }

                    if($sql){

                        $res = $this->model->query("create table $table ($sql) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci", 'u');

                        if($res){

                            $createNewTable = true;

                            if(!empty(!empty($baseTableStructure['indexes']))){

                                $this->setSqlTableIndexes($baseTableStructure['indexes'], $table);

                            }

                            if(!empty(!empty($baseTableStructure['dop_table_columns'][$table]['indexes']))){

                                $this->setSqlTableIndexes($baseTableStructure['dop_table_columns'][$table]['indexes'], $table);

                            }

                        }

                    }

                }

            }

            if($createNewTable){

                $this->redirect(PATH . Settings::get('routes')['admin']['alias']);

            }

        }

    }

    protected function setSqlTableIndexes($indexes, $table){

        if(!$indexes)
            return;

        foreach ($indexes as $key => $item){

            $type = '';

            if(!is_numeric($key)){

                $type = $item;

                $item = $key;

            }

            $char = $type ? substr(trim($type), 0, 1) : '';

            $this->model->query("create $type index {$table}_{$item}_{$char}index on $table ($item)");

        }

    }

    protected function setSqlTableKeys($keys, $table, $sql = ''){

        if(empty($keys))
            return $sql;

        foreach ($keys as $key => $item){

            $sql && $sql .= ",\n";

            if(!preg_match('/\skey/i', $key)){

                $key .= ' key';

            }

            $char = substr(trim($key), 0, 1) . 'k';

            if(!is_array($item)){

                $sql .= 'constraint ' . trim($table) . '_' . $char . " $key (" . $item . ")";

            }else{

                foreach ($item as $columnName => $values){

                    $sql .= 'constraint ' . trim($table) . '_' . $columnName . '_' . $char . " $key (" . $columnName . ")";

                    if(!empty($values['references'])){

                        $refKey = key($values['references']);

                        $refColumn = $values['references'][$refKey];

                        if(is_numeric($refKey)){

                            $refKey = $refColumn;

                            $refColumn = $columnName;

                        }

                        $sql .= " references $refKey ($refColumn)";

                        if(!empty($values['actions'])){

                            $sql .= "\n";

                            foreach ($values['actions'] as $action => $set){

                                $sql .= " on $action set $set";

                            }

                        }

                    }

                }

            }

        }

        return $sql;

    }

    protected function setSqlColumns($columns, $sql = ''){

        if(!$columns)
            return $sql;

        foreach ($columns as $columnName => $columnData){

            $sql && $sql .= ",\n";

            $sql .= "$columnName ";

            if(!empty($columnData['type']) && !empty($columnData['length'])){

                $columnData['type'] .= '(' . trim($columnData['length']) . ')';

                unset($columnData['length']);

            }

            foreach ($columnData as $key => $item){

                if($item !== false){

                    if(strtolower($key) === 'default'){

                        $item = 'default ' . $item;

                    }

                    $sql .= $item . ' ';

                }

            }

            $sql = trim($sql);


        }

        return $sql;

    }

}