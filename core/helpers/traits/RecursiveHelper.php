<?php

namespace webQHelpers\traits;

trait RecursiveHelper
{

    public static function recursiveArr($arr, $deep = 0, $parent_id = null, $row_id = 'id', $row_parent_id = 'parent_id', $recursiveName = '', $start = true){

        $set = [
            'parent_id' => $parent_id,
            'row_id' => $row_id,
            'row_parent_id' => $row_parent_id,
            'recursiveName' => $recursiveName,
            'start' => $start
        ];

        return self::recursiveBuilder($arr, $deep, $set);


    }

    public static function recursiveBuilder(array $arr, $deep = 0, array $set = []){ // работа с данными бесконечной вложенности

        !array_key_exists('parent_id', $set) && $set['parent_id'] = null;

        !array_key_exists('row_id', $set) && $set['row_id'] = 'id';

        !array_key_exists('row_parent_id', $set) && $set['row_parent_id'] = 'parent_id';

        !array_key_exists('recursiveName', $set) && $set['recursiveName'] = '';

        !array_key_exists('start', $set) && $set['start'] = true;

        !array_key_exists('events', $set) && $set['events'] = [];

        $res_arr = [];

        if(!is_array($arr))
            return $arr;

        reset($arr);

        if(is_array($deep) && isset($deep['from'])){

            $deep['from']++;

        }else{

            $deep = ['from' => 0, 'to' => $deep];

        }

        while(($key = key($arr)) !== null){

            if(is_int($set['parent_id']) && !empty($arr[$key][$set['row_parent_id']]) && is_numeric($arr[$key][$set['row_parent_id']])){

                $arr[$key][$set['row_parent_id']] = (int)$arr[$key][$set['row_parent_id']];

            }

            if(!array_key_exists($set['row_parent_id'], $arr[$key]) || $arr[$key][$set['row_parent_id']] === $set['parent_id']){

                if(empty($arr[$key]['recursive_name'])){

                    $name = $arr[$key]['name'] ?? $arr[$key][$set['row_id']];

                    $arr[$key]['recursive_name'] = $set['recursiveName'] ? $set['recursiveName'] . '->' . $name : $name;

                }

                $arr[$key]['depth_level'] = $deep['from'] ?? $deep;

                $res_arr[$arr[$key][$set['row_id']]] = $arr[$key];

                if(!empty($set['events']['insertElement']) && is_callable($set['events']['insertElement'])){

                    $set['events']['insertElement']($arr[$key], $arr);

                }

                unset($arr[$key]);

                reset($arr);

                continue;

            }

            if(isset($res_arr[$arr[$key][$set['row_parent_id']]])){

                if(!empty($set['events']['beforeInsertChildren']) && is_callable($set['events']['beforeInsertChildren'])){

                    $set['events']['beforeInsertChildren']($arr[$key], $res_arr[$arr[$key][$set['row_parent_id']]], $arr);

                }

                $recursiveSet = [
                    'parent_id' => $arr[$key][$set['row_parent_id']],
                    'row_id' => $set['row_id'],
                    'row_parent_id' => $set['row_parent_id'],
                    'recursiveName' => $res_arr[$arr[$key][$set['row_parent_id']]]['recursive_name'],
                    'start' => false,
                    'events' => $set['events']
                ];

                $res = self::recursiveBuilder($arr, $deep, $recursiveSet);

                if($res['res_arr']){

                    if($deep && is_array($deep) && !empty($deep['to']) && $deep['from'] >= $deep['to']){

                        foreach($res['res_arr'] as $item){

                            $res_arr[$item[$set['row_id']]] = $item;

                        }

                    }else{

                        $res_arr[$arr[$key][$set['row_parent_id']]]['sub'] = $res['res_arr'];

                    }

                }

                if(isset($res['arr'])){

                    $arr = $res['arr'];

                }

                if(!empty($set['events']['afterInsertChildren']) && is_callable($set['events']['afterInsertChildren'])){

                    $set['events']['afterInsertChildren']($arr[$key], $res_arr[$arr[$key][$set['row_parent_id']]], $arr);

                }

                if(isset($res['arr'])){

                    reset($arr);

                    continue;

                }

            }

            next($arr);

        }

        if($set['start'] && $arr){

            foreach ($arr as $item){

                if(empty($res_arr[$item[$set['row_id']]])){

                    $res_arr[$item[$set['row_id']]] = $item;

                    $res_arr[$item[$set['row_id']]]['old_' . $set['row_parent_id']] = $res_arr[$item[$set['row_id']]][$set['row_parent_id']];

                    $res_arr[$item[$set['row_id']]][$set['row_parent_id']] = $set['parent_id'];

                    if(!empty($set['events']['insertIncorrectElement']) && is_callable($set['events']['insertIncorrectElement'])){

                        $set['events']['insertIncorrectElement']($item, $res_arr);

                    }

                }

            }

        }

        return $set['start'] ? $res_arr : compact('res_arr', 'arr');

    }

    public static function recursiveSearch($foreignArr, $set = [], $callback = null){

        if(!is_array($set)){

            $search = $set;

            $set = [];

            $set['search'] = $search;

        }

        if(empty($foreignArr) || !is_array($foreignArr) || !array_key_exists('search', $set)){

            return null;

        }

        $set['innerRow'] = $set['innerRow'] ?? 'sub';

        $set['searchValue'] = $set['searchValue'] ?? false;

        $element = [];

        reset($foreignArr);

        while(($key = key($foreignArr)) !== null){

            if(!$set['searchValue'] && array_key_exists($set['search'], $foreignArr)){

                $element = $foreignArr[$set['search']];

            }

            if(!$element){

                if($set['searchValue']){

                    if(array_key_exists($set['searchValue'], $foreignArr[$key]) && $foreignArr[$key][$set['searchValue']] === $set['search']){

                        $element = $foreignArr[$key];

                    }

                }elseif (isset($foreignArr[$key][$set['innerRow']][$set['search']])){

                    $element = $foreignArr[$key][$set['innerRow']][$set['search']];

                }

            }


            if($element){

                if($callback && is_callable($callback)){

                    if($callback($element, $set, $foreignArr)){

                        return $element;

                    }

                }else{

                    return $element;

                }

            }

            if(!empty($foreignArr[$key][$set['innerRow']])){

                $foreignArr += $foreignArr[$key][$set['innerRow']];

                unset($foreignArr[$key][$set['innerRow']]);

            }

            if($element !== false){

                next($foreignArr);

            }else{

                $element = [];

                reset($foreignArr);

            }

        }

        return $element;

    }

}