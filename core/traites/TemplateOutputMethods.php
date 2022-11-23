<?php

namespace core\traites;

trait TemplateOutputMethods
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

        return preg_replace('/\/{2,}/', '/', \App::PATH() . $alias . \App::config()->WEB('end_slash') . $str);

    }

    protected function img($img = '', $tag = false, $set = []){

        if(!$img && is_dir($_SERVER['DOCUMENT_ROOT'] .\AppH::correctPath(\App::$webDirectory, \App::PATH(), \App::config()->WEB('upload_dir')). 'default_images')){

            $dir = scandir($_SERVER['DOCUMENT_ROOT'] .\AppH::correctPath(\App::$webDirectory, \App::PATH(), \App::config()->WEB('upload_dir')) . 'default_images');

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

    protected function translateEl($alias, $elName = 'el_name'){

        $data = $this->translateElementsAlias;

        $translateData = false;

        if(preg_match('/[а-яё]/ui', $alias)){

            $translateData = $alias;

            $alias = \AppH::translit($alias);

        }else{

            $alias = strtolower($alias);

        }

        if(!empty($data[$alias])){

            return $data[$alias][$elName] ?: $data[$alias]['name'];

        }

        if($translateData && in_array('translate_elements', $this->model->showTables())){

            $addData = [
                'name' => $translateData,
                'alias' => $alias
            ];

            if(!empty($this->model->showColumns('translate_elements')['controller'])){

                $addData['controller'] = $this->getController();

            }

            if($this->model->add('translate_elements', [
                'fields' => $addData
            ])){

                return $this->translateElementsAlias[$alias]['name'] = $translateData;

            }

        }

        return null;

    }

    public function __get($property){

        $baseProperty = $property;

        $limit = null;

        if(preg_match('/\d+$/', $property, $matches)){

            $property = preg_replace('/\d+$/', '', $property);

            $limit = $matches[0];

        }

        $property = strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1_$2', $property));

        $tables = $this->model->showTables();

        if(!in_array($property, $tables)){

            $findArr = preg_split('/_/', $property);

            if(count($findArr) === 1) return null;

            $property = '';

            $part = '';

            $count = count($findArr);

            $propertyArr = [];

            for($i = 0; $i < $count; $i++){

                array_unshift($propertyArr, array_splice($findArr, -1)[0]);

                $part = implode('_', $findArr);

                if(in_array($part, $tables)){

                    $property = $part;

                    break;

                }

            }

        }

        if($property){

            $columns = $this->model->showColumns($property);

            $order = null;

            $orderDirection = null;

            $where = null;

            if(!empty($columns['menu_position'])){

                $order = 'menu_position';

            }elseif (!empty($columns['date'])){

                $order = 'date';

                $orderDirection = 'DESC';

            }

            if(!empty($columns['visible'])){

                $where['visible'] = 1;

            }

            $this->$baseProperty = $this->model->get($property, [
                'where' => $where,
                'order' => $order,
                'order_direction' => $orderDirection,
                'limit' => $limit
            ]) ?: [];

            if(!empty($columns['external_alias']) && !empty($this->$baseProperty)){

                foreach ($this->$baseProperty as $key => $item){

                    if(!empty($item['external_alias'])){

                        if(!preg_match('/^\s*http/i', $item['external_alias'])){

                            if(preg_match('/^\s*[^\/]+\./i', $item['external_alias'])){

                                $this->$baseProperty[$key]['external_alias'] = 'http://' . $item['external_alias'];

                            }elseif (!preg_match('/^\s*\//i', $item['external_alias'])){

                                $this->$baseProperty[$key]['external_alias'] = '/' . $item['external_alias'];

                            }

                        }

                    }

                }

            }

            if(!empty($propertyArr)){

                $row = implode('_', $propertyArr);

                if(!empty($columns[$row])){

                    $resArr = [];

                    foreach ($this->$baseProperty as $key => $item){

                        if(!empty($item[$row])){

                            $resArr[$item[$row]] = $item;

                        }

                    }

                    $resArr && $this->$baseProperty = $resArr;

                }

            }

            return $this->$baseProperty;

        }

        return null;

    }

}