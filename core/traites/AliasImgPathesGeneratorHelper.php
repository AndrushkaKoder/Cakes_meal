<?php

namespace core\traites;

use core\system\Router;

trait AliasImgPathesGeneratorHelper
{

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

                if (!is_numeric($key)) {

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

        if(Router::getMode() === 'admin' && !empty(\App::config()->WEB('alias')) &&
            stripos($alias, \App::config()->WEB('alias')) !== 0){

            $alias = \App::config()->WEB('alias') . '/' . $alias;

        }

        return preg_replace('/\/{2,}/', '/', \App::PATH() . $alias . \App::config()->WEB('end_slash') . $str);

    }

    protected function img($img = '', $tag = false, $set = []){

        if(!$img && is_dir(\AppH::correctPath(\App::FULL_PATH(), \App::config()->WEB('upload_dir')). 'default_images')){

            $dir = scandir(\AppH::correctPath(\App::FULL_PATH(), \App::config()->WEB('upload_dir')). 'default_images');

            $img = preg_grep('/'.$this->getController().'\./i', $dir) ?: preg_grep('/default\./i', $dir);

            $img && $img = array_shift($img);

        }

        if($img){

            $path = \AppH::correctPath(\App::PATH(), \App::config()->WEB('upload_dir')) . $img;

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