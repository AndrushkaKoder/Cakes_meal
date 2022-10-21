<?php

namespace web\user\controllers;

use web\user\helpers\CatalogHelper;
use web\user\models\Model;

abstract class BaseUser extends \core\system\Controller
{
    use CatalogHelper;

    protected $model;

    protected $menu;

    protected function commonData(){

        $this->model = Model::instance();

        $this->menu = $this->model->get('catalog', [
            'where' => [
                'visible' => 1
            ],
            'join'=>[
                'goods' => [
                    'type' => 'inner',
                    'fields' => null,
                    'where' => [
                        'visible' => 1
                    ],
                    'on' => [
                        'id' => 'parent_id'
                    ]
                ]
            ],
            'order' => [
                'menu_position'
            ],
            'group' => 'catalog.id'

        ]);
        $a = 1;
    }



    protected function alias($alias = '', $queryString = ''){

        $str = '';

        if($queryString){

            if(is_array($queryString)){

                foreach ($queryString as $key => $item){

                    if(is_array($item)){

                        $key .= '[]';

                        foreach ($item as $v) $str .= (!$str ? '?' : '&') . $key . '=' . $v;

                    }else{

                        $str .= (!$str ? '?' : '&') . $key . '=' . $item;

                    }


                }

            }else{

                if(strpos($queryString, '?') === false) $str .= '?' . $queryString;
                else $str .= $queryString;

            }

        }

        if(is_array($alias)) {

            $aliasStr = '';

            foreach ($alias as $key => $item) {

                if (!is_numeric($key) && $item) {

                    $aliasStr .= $key . '/' . $item . '/';

                } elseif (is_numeric($key) && $item) {

                    $aliasStr .= $item . '/';

                }

            }

            $alias = trim($aliasStr, '/');

        }

        if(!$alias || $alias === '/') return \App::PATH() . $str;

        if(preg_match('/^https?:\/\//', $alias))
            return $alias . $str;

        return preg_replace('/\/{2,}/', '/', \App::PATH() . $alias . \App::WEB('end_slash') . $str);

    }

    protected function img($img = '', $tag = false, $set = []){

        if(!$img && is_dir($_SERVER['DOCUMENT_ROOT'] .\AppH::correctPath(\App::$webDirectory, \App::PATH(), \App::WEB('upload_dir')). 'default_images')){

            $dir = scandir($_SERVER['DOCUMENT_ROOT'] .\AppH::correctPath(\App::$webDirectory, \App::PATH(), \App::WEB('upload_dir')) . 'default_images');

            $img = preg_grep('/'.$this->getController().'\./i', $dir) ?: preg_grep('/default\./i', $dir);

            $img && $img = array_shift($img);

        }

        if($img){

            $path = \AppH::correctPath(\App::$webDirectory, \App::PATH(), \App::WEB('upload_dir')) . $img;

            $class = isset($set['class']) && $set['class'] ?
                ' class="' . (is_array($set['class']) ? implode(' ', $set['class']) : $set['class']) . '" ' : '';

            $alt = isset($set['alt']) && $set['alt'] ? ' alt="' . $set['alt'] . '" ' : '';

            $title = isset($set['title']) && $set['title'] ? ' title="' . $set['title'] . '" ' : '';

            $style = isset($set['style']) && $set['style'] ?
                ' style="' . (is_array($set['style']) ? implode(';', $set['style']) : $set['style']) . '" ' : '';

            $data = '';

            if(isset($set['data']) && $set['data']){

                if(is_array($set['data'])){

                    foreach($set['data'] as $key => $item){

                        if(stripos($key, 'data-') === false)
                            $data .= 'data-';

                        $data .= $key . '="' . $item . '"';

                    }

                }else{

                    if(!preg_match('/^\s*data[^=]+=/i', $set['data']))
                        $data = 'data-attribute="' . $set['data'] . '"';
                    else $data = $set['data'];

                }

            }

            if(!$tag)
                return $path;

            echo '<img src="' . $path . '"' . $alt . $title . $class . $style . ' ' . $data . ' >';

        }

        return '';

    }

}