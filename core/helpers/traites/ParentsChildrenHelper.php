<?php

namespace core\helpers\traites;

trait ParentsChildrenHelper
{

    public static function getChildren($category, $table, $idRow = null, $checkVisible = false){

        $columns = \App::model()->showColumns($table);

        !$idRow && $idRow = $columns['id_row'];

        $id = is_array($category) ? $category[$columns['id_row']] : $category;

        if(empty($columns['parent_id']))
            return $id;

        static $categoriesDb = [];

        if(empty($categoriesDb[$table])){

            $categoriesDb[$table] = \App::model()->get($table, [
                'where' => $checkVisible && !empty($columns['visible']) ? ['visible' => 1] : [],
                'order' => 'parent_id',
                'order_direction' => 'DESC'
            ]);

        }

        $categories = self::recursiveArr($categoriesDb[$table], 1, $id, $idRow);

        $ids = [];

        $ids[] = $id;

        if($categories){

            foreach($categories as $item){

                if(!array_key_exists('old_parent_id', $item) && $item['parent_id'] === $id){

                    $ids[] = $item[$columns['id_row']];

                    if(!empty($item['sub'])){

                        foreach ($item['sub'] as $subId => $value){

                            $ids[] = $subId;

                        }

                    }

                }

            }

        }

        return $ids;

    }

    public static function getParents($ids, $table, $parentRow = 'parent_id'){

        if(!$ids) return [];

        if(!is_array($ids)) $ids = (array)$ids;

        $columns = \App::model()->showColumns($table);

        if(empty($columns[$parentRow]))
            return $ids;

        $whereIds = $ids;

        while ($whereIds){

            $data =  \App::model()->get($table, [
                'fields' => [$parentRow],
                'where' => [$columns['id_row'] => $whereIds],
                'no_check_credentials' => true,
                'group' => $parentRow,
            ]);

            if(!$data){

                $whereIds = null;

                continue;

            }

            $whereIds = array_column($data, $parentRow);

            $ids = array_merge($ids, $whereIds);

            if(($keys = array_keys($ids, null))){

                foreach ($keys as $key => $item){

                    unset($ids[$item]);

                }

            }

        }

        return array_unique($ids);

    }

    public static function onlyRootParents(&$arr){

        if($arr){

            foreach ($arr as $key => $item){

                if(isset($item['parent_id']) && $item['parent_id']){

                    unset($arr[$key]);

                }

            }

        }

    }

}