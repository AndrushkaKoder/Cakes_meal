<?php

namespace webQAdmin\controller;

use webQAdminSettings\Settings;

class ShowController extends BaseAdmin
{

    protected $pagination;
    protected $filteringData;

    protected function inputData(){

        if(!$this->userData) 
            $this->execBase();

        $this->createTableData();

        $this->createForeignData();

        $this->createRadio();

        $this->createData();

        if(!empty($_GET['revision-menu_position']) && !empty($this->columns['menu_position']) && !empty($this->userData['ROOT'])){

            $this->model->revisionMenuPosition($this->table);

            \WqH::redirect($this->alias([$this->adminPath, $this->getController(), $this->table]));

        }

        return $this->expansion(get_defined_vars());

    }

    protected function createData($arr = []){

        $fields = [];
        $order = [];
        $order_direction = [];

        if(!$this->columns['id_row']) return $this->data = [];

        $fields[] = $this->columns['id_row'] . ' as id';

        if(!empty($this->columns['name'])) $fields['name'] = 'name';
        if(!empty($this->columns['img'])) $fields['img'] = 'img';

        if(count($fields) < 3){

            $name = '';
            $img = '';

            foreach ($this->columns as $key => $item){

                if((!isset($fields['name']) || !$fields['name']) && strpos($key, 'name') !== false){

                    if(!$name) $name = 'CASE ';
                    $name .= "WHEN $key <> '' THEN $key ";

                }
                if((!isset($fields['img']) || !$fields['img']) && strpos($key, 'img') === 0){

                    if(!$img) $img = 'CASE ';
                    $img .= "WHEN $key <> '' THEN $key ";

                }
            }

            if($name) $fields['name'] = $name . 'END as name';

            if($img) $fields['img'] = $img . 'END as img';

        }

        if(!isset($fields['name']) || !$fields['name']) $fields['name'] = $this->columns['id_row'] . ' as name';

        if(!empty($arr['fields'])){
            if(is_array($arr['fields'])){
                $fields = Settings::instance()->arrayMergeRecursive($fields, $arr['fields']);
            }else{
                $fields[] = $arr['fields'];
            }
        }

        $multiLevelId = null;

        if(!empty($this->columns['parent_id'])){

            if(!in_array('parent_id', $fields))
                $fields[] = 'parent_id';

            $order[] = 'parent_id';

            $keys = $this->model->showForeignKeys($this->table);

            if($keys){

                foreach ($keys as $item){

                    if($item['COLUMN_NAME'] === 'parent_id'){

                        if($item['REFERENCED_TABLE_NAME'] === $this->table) $multiLevelId = $item['REFERENCED_COLUMN_NAME'];
                        else $multiLevelId = false;

                    }

                }

            }

            if($multiLevelId === null) $multiLevelId = $this->columns['id_row'];

            if($multiLevelId === false){

                $key = array_search('parent_id', $fields);

                if($key !== false){

                    $fields[$key] .= ' as dop_parent_id';

                }

            }

        }

        if(!empty($this->columns['menu_position'])){

            $order[] = 'menu_position';

        }
        elseif (!empty($this->columns['date'])){

            if($order) $order_direction = ['ASC', 'DESC'];
            else $order_direction[] = 'DESC';

            $order[] = 'date';
        }

        if(!empty($arr['order'])){
            if(is_array($arr['order'])){
                $order = Settings::instance()->arrayMergeRecursive($order, $arr['order']);
            }else{
                $order[] = $arr['order'];
            }
        }

        if(!empty($arr['order_direction'])){
            if(is_array($arr['order_direction'])){
                $order_direction = Settings::instance()->arrayMergeRecursive($order_direction, $arr['order_direction']);
            }else{
                $order_direction[] = $arr['order_direction'];
            }
        }

        !$order && $order[] = $this->columns['id_row'];

        $where = [];

        $parentId = false;

        if(isset($_GET['filter'])){

            foreach ($_GET['filter'] as $row => $value){

                $row = \WqH::clearStr($row);

                $searchValue = $value = \WqH::clearStr($value);

                if(!empty($keys)){

                    foreach ($keys as $item){

                        if($item['COLUMN_NAME'] === $row){

                            if($row === 'parent_id'){

                                $parentId = strtolower($value) === 'null' ? null : $value;

                            }

                            $searchValue = \WqH::getChildren($value, $item['REFERENCED_TABLE_NAME'], $item['REFERENCED_COLUMN_NAME']);

                        }

                    }

                }

                $this->filteringData[$row] = $value;

                $where[$row] = $searchValue ?: false;

            }

        }

        if(!empty($this->foreignData)){

            foreach ($this->foreignData as $key => $item){

                if(!in_array($key, $fields))
                    $fields[] = $key;

            }

        }

        $result = $this->model->get($this->table, [
            'where' => $where,
            'fields' => $fields,
            'order' => $order,
            'order_direction' => $order_direction,
            'no_concat' => true
        ]);

        if($result){

            if($parentId === false){

                $parentId = null;

            }

            if(!$multiLevelId){
                $this->data = $result;
            }else{
                $this->data = \WqH::recursiveArr($result, 0, $parentId, $multiLevelId,'parent_id');
            }

        }

        if($this->data && count($this->data) > $this->countElements){

            $this->createPagination();

        }

    }

    protected function createPagination(){

        if(!isset($_GET['page'])) $page = 1;
            else $page = \WqH::clearNum($_GET['page']);

        if($page){

            $total = count($this->data);

            $number_pages = ceil($total / $this->countElements);

            $start = ($page - 1) * $this->countElements;

            array_splice($this->data, $page * $this->countElements);

            if($start){

                array_splice($this->data, 0, $start);

            }

            if ($number_pages == 1 || $page > $number_pages) {
                return false;
            }

            if ($page != 1) {
                $this->pagination['first'] = 1;
                $this->pagination['back'] = $page - 1;
            }

            if($page > $this->linksCounter + 1){
                for($i = $page - $this->linksCounter; $i < $page; $i++){
                    $this->pagination['previous'][] = $i;
                }
            }else{
                for($i = 1; $i < $page; $i++){
                    $this->pagination['previous'][] = $i;
                }
            }

            $this->pagination['current'] = $page;

            if($page + $this->linksCounter < $number_pages){
                for($i = $page + 1; $i <= $page + $this->linksCounter; $i++){
                    $this->pagination['next'][] = $i;
                }
            }else{
                for($i = $page + 1; $i <= $number_pages; $i++){
                    $this->pagination['next'][] = $i;
                }
            }

            if ($page != $number_pages) {
                $this->pagination['forward'] = $page + 1;
                $this->pagination['last'] = $number_pages;
            }

        }

    }

}