<?php
/**
 * Created by PhpStorm.
 * User: den
 * Date: 12.08.2018
 * Time: 12:12
 */

namespace libraries;


class TextModify
{

    protected $translitArr = [ 'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
                                'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
                                'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
                                'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts',
                                'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => 'y', 'ы' => 'y',
                                'ь' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', ' ' => '-',
    ];

    protected $lowelLetter = ['а', 'е', 'и', 'о', 'у', 'э'];

    protected $replaceChars = ['"', "'", ')', '(', '%', '#', '№', ',', '.', ':', '?', '&', '!', '@', '«', '»', '/', '\\', '–'];

    public function translit($str){

        $str = mb_strtolower($str);
        $temp_arr = [];

        for($i = 0; $i < mb_strlen($str); $i++){
            $temp_arr[] = mb_substr($str, $i, 1);
        }

        $link = '';

        if($temp_arr){
            foreach ($temp_arr as $key => $char){

                if(array_key_exists($char, $this->translitArr)){

                    switch($char){

                        case 'ъ':
                            if(!empty($temp_arr[$key + 1]) && $temp_arr[$key + 1] == 'е') $link .= 'y';
                            break;

                        case 'ы':
                            if(!empty($temp_arr[$key + 1]) && $temp_arr[$key + 1] == 'й') $link .= 'i';
                            else $link .= $this->translitArr[$char];
                            break;

                        case 'ь':
                            if(!empty($temp_arr[$key + 1]) && $temp_arr[$key + 1] !== count($temp_arr) && in_array($temp_arr[$key + 1], $this->lowelLetter)){
                                $link .= $this->translitArr[$char];
                            }
                            break;

                        default:
                            $link .= $this->translitArr[$char];
                            break;

                    }

                }else{

                    $link .= $char;

                }

            }

        }

        if($link){

            $link = preg_replace('/[^a-z0-9_-]/iu', '', $link);
            $link = preg_replace('/-{2,}/iu', '-', $link);
            $link = preg_replace('/_{2,}/iu', '_', $link);
            $link = preg_replace('/(^[-_]+)|([-_]+$)/iu', '', $link);

        }

        return $link;

    }

    /**
     * @param $text - строка
     * @param string $delim - html тег по которому разбивать строку
     * @param int $counter - количество элементов результирующего массива текста
     * @return array - массив, в котором текст поделен на заданное количество
     * Элементов может быть меньше, но не более чем указано в $counter
     */
    public function createTextArray($text, $delim = 'p', $counter = 4){
        if($text){
            if(is_numeric($delim)){
                $counter = (int)$delim;
                $delim = 'p';
            }

            $start_tag = '<' . $delim . '>';
            $end_tag = '</' . $delim . '>';

            $arr = explode("$start_tag", $text);
            foreach($arr as $key => $item){
                if(empty($item) || strpos($item, '&nbsp;' . $end_tag) === 0){
                    unset($arr[$key]);
                }else{
                    $arr[$key] = $start_tag . $item;
                }
            }

            $arr = array_values($arr);

            if(count($arr) == 1) return $arr;

            for(; $counter > 0; $counter--){
                $count = round(count($arr) / $counter);
                if($count >= 1) break;
            }

            $res_arr = [];

            $j = 0;
            for($i = 0; $i < $counter; $i++) {
                foreach ($arr as $index => $item) {
                    if($j < $count){
                        $res_arr[$i] .= $item;
                        unset($arr[$index]);
                        $j++;
                    }else{
                        $j = 0;
                        break;
                    }
                }
            }
            if(!empty($arr)){
                foreach($arr as $item){
                    $res_arr[$counter - 1] .= $item;
                }
            }
            return $res_arr;
        }
        return $text;
    }

    /**
     * @param $str - Строка для обрезки по заданным параметра
     * @param bool $counter - количество символов для обрезки, по умолчанию половина текста
     * @return array - массив => [0 => первая половина текста, 1 => вторая половина текста]
     */
    public function textCutting($str, $counter = false, $revers = false, $add_tags = true, $concat_str = ''){

        $tags = ['p', 'ul', 'ol'];

        if(!$counter){
            $center = mb_strlen($str) / 2;
            $center = (int)$center;
            $char = mb_substr($str, $center, 1);
            $res_str = array();
            if($char != ' '){
                if(!$revers){
                    $sub_str = mb_substr($str, $center);
                    $probel = (int)mb_strpos($sub_str, ' ');
                }else{
                    $sub_str = mb_substr($str, 0, $center);
                    $probel = (int)mb_strrpos($sub_str, ' ');
                }

                if($probel){
                    if(!$revers){
                        $cut = $center + $probel;
                    }else{
                        $cut = $probel;
                    }

                    $res_str[0] = mb_substr($str, 0, $cut);
                    $res_str[1] = mb_substr($str, $cut + 1);
                }else{
                    $res_str[0] = $str;
                }
            }else{
                $res_str[0] = mb_substr($str, 0, $center);
                $res_str[1] = mb_substr($str, $center + 1);
            }
        }else{

            if(mb_strlen($str) < $counter){
                $res_str[0] = $str;
                return $res_str;
            }
            $center = $counter;
            $char = mb_substr($str, $center, 1);
            $res_str = array();

            if($char != ' '){
                $sub_str = mb_substr($str, 0, $center);
                $probel = (int)mb_strrpos($sub_str, ' ');
                if($probel){
                    $res_str[0] = mb_substr($str, 0, $probel);
                    $res_str[1] = mb_substr($str, $probel + 1);
                }else{
                    $res_str[0] = $str;
                }
            }else{
                $res_str[0] = mb_substr($str, 0, $center);
                $res_str[1] = mb_substr($str, $center + 1);
            }
        }

        if($add_tags){

            foreach($res_str as $key => $item){

                foreach($tags as $tag){

                    preg_match_all('/<' . $tag .  '[^>]*>/is', $item, $starts);

                    $start = !empty($starts[0]) ? mb_strrpos($item, $starts[0][count($starts[0]) - 1]) : false;

                    preg_match_all('/<\/' . $tag .  '[^>]*>/is', $item, $ends);

                    $end = !empty($ends[0]) ? mb_strrpos($item, $ends[0][count($ends[0]) - 1]) : false;

                    if ($end !== false){

                        if($start === false){

                            $res_str[$key] = '<' . $tag . '>' . $concat_str . $res_str[$key];

                        }elseif($start > $end){

                            $res_str[$key] .= $concat_str . '</' . $tag . '>';

                        }

                    }elseif($start !== false){

                        $end = (int)$end;

                        if($start <= $end){

                            $res_str[$key] .= $concat_str . '</' . $tag . '>';

                        }
                    }
                }
            }

        }


        return $res_str;
    }

    public function mbUcFirst($str, $enc = 'utf-8') {
        return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc);
    }

}