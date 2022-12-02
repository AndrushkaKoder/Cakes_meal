<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 12.12.2019
 * Time: 15:03
 */

namespace core\admin\controller;


use core\base\settings\Settings;
use libraries\TextModify;

class SingleparserController extends BaseAdmin{

    protected function inputData()
    {
        $this->execBase();

        if(!$this->isPost()) $this->redirect();

        include_once $_SERVER['DOCUMENT_ROOT'] . PATH . 'libraries/simple_html_dom.php';

        $html = new \simple_html_dom();

        for($i = 0; $i < 5; $i++){

            $page = $this->getRemotePage();

            if($page) break;

        }

        if(!$page){

            $_SESSION['res']['answer'] = '<div class="error">Возможность автоматического заполнения полей недоступна. Попробуйте еще раз</div>';
            $this->redirect();

        }

        $arr = [];

        $html->load($page);

        $arr['name'] = trim($html->find('#inbreadcrumb ul' ,0)->last_child()->plaintext);
        $arr['title'] = trim($html->find('title' ,0)->plaintext);
        $arr['description'] = trim($html->find('meta[name=description]', 0)->content);

        $arr['category'] = trim($html->find('h1' ,0)->plaintext);
        $arr['category'] = trim(mb_substr($arr['category'], 0, mb_strpos($arr['category'], $arr['name'])));

        $arr['price'] = trim($html->find('.product-body__title' ,0)->plaintext);

        $arr['price'] = str_replace(',', '.', $arr['price']);
        $arr['price'] = $this->clearNum(preg_replace('/[^\d.]/', '', $arr['price']));

        $i = 0;

        foreach ($html->find('.tab-inner .prop-row') as $item){

            $name = mb_strtolower(trim($item->find('.prop-title', 0)->plaintext));

            $values = $item->find('.prop-value a');

            $value = [];

            if($values){

                foreach ($values as $a){

                    $val = mb_strtolower(trim($a->plaintext));

                    if($val) $value[] = $val;

                }

            }else{

                $value[] = mb_strtolower(trim($item->find('.prop-value', 0)->plaintext));

            }

//            if(preg_match('/[\.,:;\!\?].*/iu', $name, $matches)){
//
//                $name = preg_replace('/[\.,:;\!\?].*/iu', '', $name);
//                $value .= mb_substr($matches[0], 1);
//
//            }

            if(!$values && preg_match('/(\s*(\d)+\s*(\w*[\.,].+))|(\w+([\.,:;\!\?].*))/iu', $name, $matches)){

//                if($matches[1]){
//
//                    $name = mb_substr($name, 0, mb_strpos($name, $matches[1]));
//                    $value .= $matches[3];
//
//                }
                if(isset($matches[5])){

                    $name = mb_substr($name, 0, mb_strpos($name, $matches[5]));

                    foreach ($value as $num => $v){

                        if(preg_match('/\d/', $v)){

                            $value[$num] .= mb_substr($matches[5], 1);

                        }

                    }

                }

            }

            if($name && $value){

                $arr['characters'][$i]['name'] = $name;
                $arr['characters'][$i]['value'] = $value;

                $i++;

            }

        }

        if($arr){

            $res = $this->model->get('filters', [
                'fields' => ['id', 'name', 'parent_id'],
                'order' => ['parent_id', 'id']
            ]);

            $filters = [];

            if($res){

                foreach ($res as $item){

                    if(!$item['parent_id']){

                        $filters[$item['id']] = $item;

                    }else{

                        $filters[$item['parent_id']]['sub'][] = $item;

                    }

                }

                $textModify = new TextModify();

                $filterArr = [];

                if($arr['characters']){

                    foreach ($arr['characters'] as $key => $item){

                        $name = $item['name'];

                        foreach ($filters as $filter){

                            $filter_name = trim(mb_strtolower($filter['name']));

                            if($filter_name === $name){

                                if(isset($filter['sub'])){

                                    foreach ($filter['sub'] as $sub){

                                        $filter_sub_name = mb_strtolower(trim($sub['name']));

                                        foreach ($item['value'] as $num => $value){

                                            if($value === $filter_sub_name){

                                                $filterArr['exists_items'][$sub['id']] = $sub['name'];

                                                unset($item['value'][$num]);

                                            }

                                        }

                                    }

                                }

                                if($item['value']){

                                    foreach ($item['value'] as $val){

                                        $filterArr['exists_root'][$filter['id']][] = $textModify->mbUcFirst($val);

                                    }

                                }

                                continue 2;

                            }

                        }

                        $filterArr['items'][$textModify->mbUcFirst($name)] = $item['value'];

                    }


                    $_POST['table'] = 'good';

                    $exists_filters = [];

                    if($_POST['id']){

                        $_POST['id'] = $this->clearNum($_POST['id']);

                        if($_POST['id']){

                            $data = $this->model->get('good', [
                                'where' => ['id' => $_POST['id']]
                            ])[0];

                            if($data){

                                foreach($arr as $key => $item){

                                    if(!array_key_exists($key, $data)){

                                        unset($arr[$key]);
                                        continue;

                                    }else{

                                        if($item) $arr[$key] = $item;

                                    }

                                }

                                if($data['alias']) $arr['alias'] = $data['alias'];

                                $temp = $this->model->get('filters_good', [
                                    'fields' => ['filters_id'],
                                    'where' => ['good_id' => $_POST['id']]
                                ]);

                                if($temp){

                                    foreach ($temp as $item){

                                        $exists_filters[] = $item['filters_id'];

                                    }

                                }

                            }

                        }else{

                            $_POST['id'] = null;

                        }

                    }

                    $_POST['filters'][0] = $this->addNewFilters($filterArr);

                    if($exists_filters){

                        foreach($exists_filters as $key => $item){

                            if(!in_array($item, $_POST['filters'][0])) $_POST['filters'][0][] = $item;

                        }

                    }

                    if(isset($arr['category']) && $arr['category']){

                        $_POST['parent_id'] = $category = $this->model->get('categories', [
                            'fields' => ['id'],
                            'where' => ['{%LIKE%}name' => $arr['category']],
                            'limit' => 1
                        ])[0]['id'];

                    }

                    if(!$_POST['parent_id'] && !$_POST['id']){

                        $_POST['parent_id'] = $this->model->get('categories', [
                            'fields' => ['id'],
                            'order' => ['id'],
                            'limit' => 1
                        ])[0]['id'];

                    }

                    if($arr){

                        foreach ($arr as $key => $item){

                            $_POST[$key] = $item;

                        }

                    }

                    $id = $this->checkPost(false,true);

                    if(is_numeric($id)) $this->redirect(PATH . Settings::get('routes')['admin']['alias'] . '/edit/good/' . $id);
                        else $this->redirect();

                }

            }

        }

    }

    protected function addNewFilters($filters){

        $ids = [];

        $textModify = new TextModify();

        if($filters['exists_items']) $ids = array_keys($filters['exists_items']);

        if($filters['exists_root']){

            foreach ($filters['exists_root'] as $key => $item){

                $count = $this->model->get('filters', [
                    'fields' => ['COUNT(*) as count'],
                    'where' => ['parent_id' => $key],
                    'no_concat' => true
                ])[0]['count'] + 1;

                foreach ($item as $el){

                    $el = $textModify->mbUcFirst($el);

                    $alias = $textModify->translit($el);

                    $id = $this->model->add('filters', [
                        'fields' => ['name' => $el, 'menu_position' => $count, 'parent_id' => $key, 'alias' => $alias],
                        'return_id' => true
                    ]);

                    if(is_numeric($id)) $ids[] = $id;

                    $count++;

                }

            }

        }

        if($filters['items']){

            foreach ($filters['items'] as $key => $item){

                $parent_id = false;

                foreach ($item as $el_count => $el){

                    $el_count++;

                    $el = preg_replace('/\s+/', ' ', mb_strtolower($el));

                    $filter = false;

//                    if(mb_strlen($el) > 3 && !preg_match('/\d+/iu', $el)){
//
//                        $filter = $this->model->get('filters', [
//                            'fields' => ['id', 'name', 'parent_id'],
//                            'where' => ['{%LIKE%}name' => addslashes($el), 'parent_id' => null],
//                        ]);
//
//                    }

                    if($filter){

                        $new_item = preg_replace('/([\.\/\?\!\{\}\(\)])/u', '\$1', $el);

                        $variants = [];

                        foreach ($filter as $value){

                            if(preg_match('/(^|\s+)' . $new_item . '(\s+|$)/iu', $value['name'])){

                                $variants[$value['id']] = mb_strlen($value['name']) / mb_strlen($el);

                            }

                        }

                        if($variants){

                            asort($variants);

                            if(reset($variants) < 2){

                                $id = array_keys($variants)[0];

                                if(!in_array($id, $ids)) $ids[] = $id;

                            }

                        }

                    }else{

                        if($parent_id === false){

                            $alias = $textModify->translit($key);

                            $count = $this->model->get('filters', [
                                    'fields' => ['COUNT(*) as count'],
                                    'where' => ['parent_id' => null],
                                    'no_concat' => true
                                ])[0]['count'] + 1;

                            $parent_id = $this->model->add('filters', [
                                'fields' => ['name' => $key, 'menu_position' => $count, 'alias' => $alias],
                                'return_id' => true
                            ]);

                        }

                        if(is_numeric($parent_id)){

                            $alias = $textModify->translit($el);

                            if($this->model->get('filters', ['where' => ['alias' => $alias], 'limit' => 1])){

                                $alias = $textModify->translit($key . '-' . $el);

                            }

                            $el = $textModify->mbUcFirst($el);

                            $id = $this->model->add('filters', [
                                'fields' => ['name' => $el, 'parent_id' => $parent_id, 'menu_position' => $el_count, 'alias' => $alias],
                                'return_id' => true
                            ]);

                            if(!in_array($id, $ids)) $ids[] = $id;

                        }

                    }

                }

            }

        }

        return $ids;

    }

    protected function getRemotePage(){

        $url = $_POST['site_address'];
        $headers = ['User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        //curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'] . PATH . 'log/cookie/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'] . PATH . 'log/cookie/cookie.txt');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');

        return curl_exec($ch);

    }

}