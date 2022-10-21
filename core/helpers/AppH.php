<?php


class AppH
{

    public static function scanDir(string $path, callable $callback){

        if(file_exists($path)){

            $list = scandir($path); //сюда возвращается список директорий

            foreach ($list as $file){

                if($file !== '.' && $file !== '..'){

                    $callback($file, $path); // если у файла нет . .. то вызывваем колбэк

                }

            }

        }

    }

    public static function clearNum($num){ // обработка входящих чисел. Вернет или число в нужной форме, или 0

        return (isset($num) && $num && preg_match('/\d/', $num)) ? preg_replace('/[^\d.]/', '', str_replace(',', '.', $num)) * 1 : 0;

    }

    public static function clearStr(string $str, $ecran = true) :string{ //обработка строк и защита от инъекций

        return $ecran ? str_replace(array("\\","\0","\n","\r","\x1a","'",'"'),array("\\\\","\\0","\\n","\\r","\Z","\'",'\"'), trim(strip_tags($str))) : trim(strip_tags($str));

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

    public static function correctPath(){
        $path = '';
        foreach (func_get_args() as $item){
            $path .= '/' . $item . '/';
        }
        return preg_replace('/\/{2,}/', '/', $path);
    }

}

