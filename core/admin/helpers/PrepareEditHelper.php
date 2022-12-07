<?php

namespace core\admin\helpers;

use core\models\Crypt;
use settings\Settings;

trait PrepareEditHelper
{

    protected function createAlias($id = false, $arr = []){

        if(!$arr){

            if(!empty($_POST)){

                $arr = &$_POST;

            }else{

                return $arr;

            }

        }

        if(!empty($this->columns['alias'])){

            $link_str = '';

            if(empty($arr['alias'])){

                if($id && !isset($arr['alias']) && !empty($this->columns['id_row'])){

                    $dbRes = $this->model->get($this->table, [
                        'fields' => ['alias'],
                        'where' => [$this->columns['id_row'] => $id],
                        'single' => true,
                        'no_check_credentials' => true
                    ]);

                    if($dbRes['alias']) return $arr;

                }

                $rowAlias = Settings::get('rowAlias');

                if(!empty($rowAlias[$this->table]) && !empty($arr[$rowAlias[$this->table]])){

                    $link_str = $arr[$rowAlias[$this->table]];

                }elseif(!empty($arr['name'])){

                    $link_str = $arr['name'];

                }else{

                    foreach($arr as $key => $item){

                        if(mb_stripos($key, 'name') !== false && $item){

                            $link_str = $item;

                            break;

                        }
                    }

                }

            }else{

                $link_str = $arr['alias'] = \AppH::clearStr($arr['alias']);

            }

            if(!$link_str) return $arr;

            $textModify = new \libraries\TextModify();

            $maxLength = 0;

            if(!empty($this->columns['alias']['Type']) && preg_match('/\(\d+\)/', $this->columns['alias']['Type'], $matches)){

                $maxLength = \AppH::clearNum($matches[0]);

            }

            $alias = $textModify->translit($link_str);

            $maxLength && $alias = preg_replace('/-+$/', '', substr($alias, 0, $maxLength));

            $where['alias'] = $alias;

            if($id){

                $where['!' . $this->columns['id_row']] = $id;

            }

            $res_link = $this->model->get($this->table, [
                'fields' => ['alias'],
                'where' => $where,
                'limit' => '1',
                'single' => true,
                'no_check_credentials' => true
            ]);

            if(!$res_link){

                $arr['alias'] = $alias;

            }else {

                $this->createForeignData();

                if(empty($this->foreignData)){

                    $this->alias = $alias;

                    $arr['alias'] = '';

                }else{

                    $parentRow = 'parent_id';

                    uksort($this->foreignData, function($a, $b) use ($parentRow){

                        return (int)in_array($b, (array)$parentRow);

                    });

                    $fullName = '';

                    foreach ($this->foreignData as $row => $foreignArr){

                        if(!empty($arr[$row]) && trim(strtolower((string)$arr[$row])) !== 'null' && !empty($foreignArr)){

                            $targetElement = \AppH::recursiveSearch($foreignArr,$arr[$row]);

                            $fullName = $targetElement['recursive_name'] ?? ($targetElement['recursive_name'] ?? '');

                            if(!$fullName){

                                continue;

                            }else{

                                $fullNameArr = preg_split('/->/', $fullName, 0, PREG_SPLIT_NO_EMPTY);

                                $preName = '';

                                foreach ($fullNameArr as $value){

                                    $preName && $preName .= '-';

                                    $preName .= $value;

                                    $where['alias'] = $textModify->translit($preName . '-' . $alias);

                                    $maxLength && $where['alias'] = preg_replace('/-+$/', '', substr($where['alias'], 0, $maxLength));

                                    $res_link = $this->model->get($this->table, [
                                        'fields' => ['alias'],
                                        'where' => $where,
                                        'limit' => '1',
                                        'single' => true,
                                        'no_check_credentials' => true
                                    ]);

                                    if(!$res_link){

                                        $arr['alias'] = $where['alias'];

                                        break 2;

                                    }

                                }

                                $fullName = '';

                            }

                        }

                    }

                    if(!$fullName){

                        $this->alias = $alias;

                        $arr['alias'] = '';

                    }

                }

            }

            if($arr['alias'] && $id){

                $this->checkOldAlias($id);

            }

        }

        return $arr;

    }

    protected function checkAlias($id){

        if($id){

            if($this->alias){

                $this->alias = $this->alias . '_' . $id;

                $this->model->edit($this->table, [
                    'fields' => ['alias' => $this->alias],
                    'where' => [$this->columns['id_row'] => $id]
                ]);
            }

            return true;
        }

        return false;
    }

    protected function checkOldAlias($id){

        $tables = $this->model->showTables();

        if(in_array('old_alias', $tables)){

            $old_alias = $this->model->get($this->table, [
                'fields' => ['alias'],
                'where' => [$this->columns['id_row'] => $id]
            ])[0]['alias'];

            if($old_alias && $old_alias !== $_POST['alias']){

                $this->model->delete('old_alias', [
                    'where' => ['alias' => $old_alias, 'table_name' => $this->table]
                ]);

                $this->model->delete('old_alias', [
                    'where' => ['alias' => $_POST['alias'], 'table_name' => $this->table]
                ]);

                $this->model->add('old_alias', [
                    'fields' => ['alias' => $old_alias, 'table_name' => $this->table, 'table_id' => $id]
                ]);

            }
        }

    }

    protected function updateMenuPosition($id = false, $old_data = false){

        if(empty($this->userData['manual_menu_position'])){

            $where = false;

            if($id && !empty($this->columns['id_row'])){
                $where = [$this->columns['id_row'] => $id];
            }

            if(array_key_exists('menu_position', $_POST)){

                $this->model->updateMenuPosition($this->table, [
                    'where' => $where,
                    'old_data' => $old_data
                ]);

            }

        }

    }

    protected function checkPost($settings = false, $returnId = false){
        if(\AppH::isPost()){
            $this->clearPostFields();
            $this->table = $_POST['table'];
            unset($_POST['table']);

            if($this->table){
                $this->createTableData($settings);
                return $this->editData($returnId);
            }
        }
    }

    protected function countChar($value, $counter, $answer){
        if(mb_strlen($value) > $counter){

            $str = preg_replace('/\$1/', $answer, $this->messages['count']);
            $str = preg_replace('/\$2/', $counter, $str);

            $_SESSION['res']['answer'] = '<div class="error">' . $str . '</div>';
            $this->addSessionData();
        }
    }

    protected function clearPostFields($arr = [], $settings = false, $deep = 0){

        if(!$arr) $arr = &$_POST;
        if(!$settings) $settings = Settings::instance();

        $validate = $settings::get('validation');
        if(!$this->translate) $this->translate = $settings::get('translate');

        $lang = $settings::get('multiLanguage');

        $columns = $this->model->showColumns($this->table);

        foreach($arr as $key => $value){
            if(is_array($value)){
                $this->clearPostFields($value, $settings, ++$deep);
            }else{

                if(is_numeric($value)){
                    $arr[$key] = \AppH::clearNum($value);
                }

                if(isset($columns[$key]['Type']) && preg_match('/(int(\(\d*\))|($))|(float)/i', $columns[$key]['Type']) && $value === ''){

                    if($columns[$key]['Default'] === null){

                        $arr[$key] = null;

                    }
                    else{

                        unset($arr[$key]);
                        continue;

                    }

                }

                if($validate){

                    if($lang){

                        foreach ($lang as $lang_name => $lang_value){

                            $lang_name = str_replace('-', '_', $lang_name);

                            if(strpos($key, $lang_name . '_') === 0){

                                $base_name = str_replace($lang_name . '_', '', $key);

                                if(array_key_exists($base_name, $validate) && !array_key_exists($key, $validate)){

                                    $validate[$key] = $validate[$base_name];

                                    unset($validate[$key]['empty']);

                                    if(!empty($this->translate[$base_name]) && empty($this->translate[$key])){

                                        $this->translate[$key] = $this->translate[$base_name];
                                        $this->translate[$key][0] .= "($lang_value)";

                                    }


                                }

                            }

                        }

                    }

                    if(array_key_exists($key, $validate)){

                        if(!empty($this->translate[$key])){
                            $answer = $this->translate[$key][0];
                        }else{
                            $answer = $key;
                        }

                        if(!empty($validate[$key]['crypt'])){
                            if(empty($value)){
                                unset($arr[$key]);
                                continue;
                            }

                            $arr[$key] = Crypt::pwd($value);
                        }

                        if(!empty($validate[$key]['empty']) && !$deep){
                            $this->emptyFields($value, $answer);
                        }

                        if(!empty($validate[$key]['trim'])){
                            $arr[$key] = trim($value);
                        }

                        if(!empty($validate[$key]['int'])){
                            if(preg_match('/(int\(\d*\))|(float)/i', $columns[$key]['Type'])){

                                $arr[$key] = \AppH::clearNum($value);

                            }else{

                                $arr[$key] = trim($value);

                            }
                        }

                        if(!empty($validate[$key]['count']) && !$deep){
                            $this->countChar($value,  $validate[$key]['count'], $answer);
                        }
                    }
                }
            }
        }

        return true;
    }

    protected function createMenuPosition($settings = false){

        if(!empty($this->columns['menu_position'])){

            if(!$settings) $settings = Settings::instance();
            $rootItems = $settings::get('rootItems');

            $where = [];

            if(!empty($this->columns['parent_id'])){

                if($this->data){

                    $where = ['parent_id' => $this->data['parent_id']];

                }else{

                    if(in_array($this->table, $rootItems['tables'])){

                        $where = ['parent_id' => false];

                    }else{

                        $parent = $this->model->showForeignKeys($this->table, 'parent_id')[0];

                        if($parent){

                            if($this->table === $parent['REFERENCED_TABLE_NAME']){
                                $where = ['parent_id' => false];
                            }else{

                                $columns = $this->model->showColumns($parent['REFERENCED_TABLE_NAME']);

                                if($columns['parent_id']) $order[] = 'parent_id';
                                else $order[] = $parent['REFERENCED_COLUMN_NAME'];

                                $id = $this->model->get($parent['REFERENCED_TABLE_NAME'], [
                                    'fields' => [$parent['REFERENCED_COLUMN_NAME']],
                                    'order' => $order,
                                    'limit' => '1'
                                ])[0][$parent['REFERENCED_COLUMN_NAME']];

                                if($id) $where = ['parent_id' => $id];

                            }

                        }else{

                            $where = ['parent_id' => false];

                        }

                    }

                }

            }

            $menu_pos = $this->model->get($this->table, [
                'fields' => ['COUNT(*) as count'],
                'where' => $where,
                'no_concat' => true
            ])[0]['count'] ?? 0;

            if(!$this->data) $menu_pos++;

            for($i = 1; $i <= $menu_pos; $i++){
                $this->foreignData['menu_position'][$i - 1]['id'] = $i;
                $this->foreignData['menu_position'][$i - 1]['name'] = $i;
            }

        }

        return;

    }

}