<?php

namespace web\user\helpers;

trait CatalogHelper
{

//    use CartHelper;

    protected function getGoods($where, &$catalogFilters = null, &$catalogPrices= null){

        if(empty($where['where']['visible'])){

            $where['where']['visible'] = 1;

        }

        $customValues = [];

        if(!empty($_GET[$this->model->filtersTable]) && is_array($_GET[$this->model->filtersTable])){

            foreach ($_GET[$this->model->filtersTable] as $key => $item){

                if(!is_numeric($item)
                    && preg_match('/^\d+_value_.+/', $item)
                    && !empty($this->model->showColumns($this->model->filtersGoodsTable)[$this->model->goodsTable . '_value'])){

                    $itemArr = preg_split('/_value_/', $item, 2, PREG_SPLIT_NO_EMPTY);

                    $_GET[$this->model->filtersTable][$key] = \AppH::clearNum($itemArr[0]);

                    $customValues[$_GET[$this->model->filtersTable][$key]][$itemArr[1]] = true;

                }else{

                    $_GET[$this->model->filtersTable][$key] = is_numeric($item) ? \AppH::clearNum($item) : \AppH::clearStr($item);

                }

                if(empty($_GET[$this->model->filtersTable][$key])){

                    unset($_GET[$this->model->filtersTable][$key]);

                    continue;

                }

                $other = array_search($_GET[$this->model->filtersTable][$key], $_GET[$this->model->filtersTable]);

                if($other !== false && $other !== $key){

                    unset($_GET[$this->model->filtersTable][$key]);

                }

            }

            $res = $this->model->get($this->model->filtersTable, [
                'where' => ['id' => $_GET[$this->model->filtersTable]],
                'join' => [
                    $this->model->filtersParentTable . ' parent' => [
                        'fields' => ['id as p_id', 'name as p_name'],
                        'on' => ['parent_id' => 'id']
                    ]
                ],
                'distinct' => true
            ]);

            if($res){

                $arr = [];

                foreach ($res as $item){

                    if(empty($item['p_id'])){

                        $item['p_id'] = $item['id'];

                    }

                    if(empty($arr[$item['p_id']])){

                        $arr[$item['p_id']] = [];

                    }

                    if(!in_array($item['id'], $arr[$item['p_id']])){

                        $arr[$item['p_id']][] = $item['id'];

                    }

                }

                $resArr = $this->crossDiffArr($arr);

                if($resArr){

                    $queryStr = '';

                    $filtersCount = 0;

                    foreach ($resArr as $key => $item){

                        !$filtersCount && $filtersCount = count($item);

                        $IN = '';

                        $CUSTOM = '';

                        foreach ($item as $id){

                            if(empty($customValues[$id])){

                                $IN .= ($IN ? ',' : '') . $id;

                            }else{

                                foreach ($customValues[$id] as $k => $v){

                                    $CUSTOM .= ($CUSTOM ? ' OR ' : '') . '(' . $this->model->filtersColumn . ' = ' . $id . ' AND ' . $this->model->goodsTable . '_value = ' . "'{$k}'" . ')';

                                }

                            }

                        }

                        $IN && $IN = "{$this->model->filtersColumn} IN({$IN})";

                        $CUSTOM && $IN && $CUSTOM = ' OR ' . $CUSTOM;

                        $queryStr .= ' (' . $IN . $CUSTOM . ')' . (isset($resArr[$key + 1]) ? ' OR' : '');

                    }

                    $where['where']['{IN}id'] = $this->model->get($this->model->filtersGoodsTable, [
                        'fields' => [$this->model->goodsColumn],
                        'where' => $queryStr,
                        'group' => $this->model->goodsColumn . ' HAVING COUNT(' . $this->model->goodsColumn . ') >= ' . $filtersCount,
                        'return_query' => true
                    ]);

                }

            }

        }

        if(isset($_GET['min_price']) && isset($_GET['max_price'])){

            $where['where']['><price'] = [\AppH::clearNum($_GET['min_price']), \AppH::clearNum($_GET['max_price'])];

        }

        if(!empty($_GET['order'])){

            $orderArr = preg_split('/_+/', $_GET['order'], 2);

            $where['order'] = $orderArr[0] . ' ' . ($orderArr[1] ?? '');

        }

        if(empty($where['pagination'])){

            $where['pagination'] = \AppH::clearNum($_GET['page'] ?? 2) ?? 2;

        }

        $data = $this->model->getGoods($where, $catalogFilters, $catalogPrices);

        if(!$data && !empty($where['where']['parent_id']) && empty($where['not_find_child_catalog'])){

            $ids = [];

            $categories = (array)$where['where']['parent_id'];

            foreach ($categories as $id){

                $res = $this->getChildren($id, $this->model->catalogTable, null, true);

                $res && $ids = array_merge($ids, (array)$res);

            }

            $where['where']['parent_id'] = array_unique($ids);

            $data = $this->model->getGoods($where, $catalogFilters, $catalogPrices);

        }

        return $data;

    }

    protected function crossDiffArr($arr){

        if(empty($arr))
            return [];

        if(count($arr) === 1)
            return array_chunk(array_shift($arr), 1);

        $result = [[]];

        foreach ($arr as $property => $property_values) {

            $tmp = [];

            $property_values = (array)$property_values;

            foreach ($result as $result_item) {

                foreach ($property_values as $property_value) {

                    $tmp[] = array_merge($result_item, [$property => $property_value]);

                }

            }

            $result = $tmp;
        }

        return $result;

    }

    protected function showGoods($data, $parameters = [], $template = 'goodsItem'){

        if(!empty($data)){

            echo $this->render(TEMPLATE . 'include/' . $template, compact('data', 'parameters'));

        }

    }

}