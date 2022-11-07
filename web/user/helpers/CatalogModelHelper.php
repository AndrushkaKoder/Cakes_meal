<?php

namespace web\user\helpers;

trait CatalogModelHelper
{

    public $userData = [];

    public $goodsUnits = ['шт'];

    public $catalogTable = 'catalog';

    public $goodsTable = 'goods';

    public $filtersTable = 'filters';

    public $filtersParentTable = 'filters';

    public $offersTable = 'offers';

    public $filtersGoodsTable = 'filters_goods';

    public $filtersColumn = 'filters_id';

    public $goodsColumn = 'goods_id';

    public function getGoods($set, &$catalogFilters = null, &$catalogPrices = null){

        if(empty($set['join_structure'])){

            $set['join_structure'] = true;

        }

        if(empty($set['where'])){

            $set['where'] = [];

        }

        if(empty($set['order'])){

            $set['order'] = '';

            if(!empty($this->showColumns($this->goodsTable)['parent_id'])){

                $set['order'] = 'parent_id ASC';

            }

            if(!empty($this->showColumns($this->goodsTable)['price'])){

                $set['order'] && $set['order'] .= ',';

                $set['order'] .= 'if(' . $this->goodsTable . '.price > 0, 1, 0) DESC, ' . $this->goodsTable . '.price';

            }

        }

        $single = false;

        if(!empty($set['single'])){

            $single = true;

            unset($set['single']);

        }

        $goods = $this->get($this->goodsTable, $set);

        if($goods){

            if($this->offersTable && in_array($this->offersTable, $this->showTables())){

                $offersWhere['parent_id'] = array_keys($goods);

                $offersOrder = [];

                if(!empty($this->showColumns($this->offersTable)['visible'])){

                    $offersWhere['visible'] = 1;

                }

                if(!empty($this->showColumns($this->offersTable)['price'])){

                    $offersOrder[] = 'price';

                }

                $offers = $this->get($this->offersTable, [
                    'where' => $offersWhere,
                    'order' => $offersOrder
                ]);

                $this->setOffers($goods, $offers);

            }

            $parameters = $set;

            $parameters['single'] = true;

            unset($parameters['join_structure'], $parameters['join'], $parameters['pagination'], $parameters['limit']);

            if($catalogPrices !== false && !empty($this->showColumns($this->goodsTable)['price'])){

                $parameters['fields'] = ['MIN(price) as min_price', 'MAX(price) as max_price'];

                $catalogPrices = $this->get($this->goodsTable, $parameters);

            }

            if($catalogFilters !== false && in_array($this->filtersTable, $this->showTables())){

                $filtersWhere = [];

                $parentFiltersWhere = [];

                $parentFiltersFields = [];

                $parentFiltersCondition = [];

                foreach ($this->showColumns($this->filtersParentTable) as $name => $value){

                    if(!empty($value) && is_array($value)){

                        $parentFiltersFields[] = $name . ' as f_' . $name;

                    }

                }

                if(!empty($this->showColumns($this->filtersParentTable)['visible'])){

                    $parentFiltersWhere['(visible'] = 1;

                    $parentFiltersWhere[')' . $this->filtersTable . '.parent_id'] = null;

                    $parentFiltersCondition = ['OR'];

                }

                if(!empty($this->showColumns($this->filtersTable)['visible'])){

                    $filtersWhere['visible'] = 1;

                }

                $filersOrder = [];

                if(!empty($this->showColumns($this->filtersParentTable)['menu_position'])){

                    $filersOrder[] = 'f_name.menu_position';

                }

                if(!empty($this->showColumns($this->filtersTable)['menu_position'])){

                    $filersOrder[] = $this->filtersTable . '.menu_position';

                }

                $filters = $this->get($this->filtersTable, [
                    'where' => $filtersWhere,
                    'join' => [
                        $this->filtersParentTable . ' f_name' => [
                            'fields' => $parentFiltersFields,
                            'where' => $parentFiltersWhere,
                            'condition' => $parentFiltersCondition,
                            'on' => ['parent_id' => 'id']
                        ],
                        $this->filtersGoodsTable => [
                            'on' => [
                                'table' => $this->filtersTable,
                                'fields' => ['id' => $this->filtersColumn]
                            ],
                            'where' => [
                                $this->goodsColumn => $this->get($this->goodsTable, [
                                    'fields' => [$this->showColumns($this->goodsTable)['id_row']],
                                    'where' => ($parameters['where'] ?? null),
                                    'condition' => ($parameters['condition'] ?? null),
                                    'return_query' => true
                                ])
                            ]
                        ]
                    ],
                    'order' => $filersOrder,
                    'no_concat_order' => true
                ]);

            }

            $applyDiscount = false;

            if($this->userData && (!empty($this->userData['discount']) || !empty($this->userData['visitor_type']))){

                foreach ($goods as $key => $item){

                    if(!empty($this->userData['visitor_type']) && !empty($item['price_opt']) && array_key_exists('price', $item)){

                        $goods[$key]['price'] = $item['price'] = $item['price_opt'];

                    }

                    if(!empty($this->userData['discount'])){

                        $applyDiscount = true;

                        $this->applyGoodsDiscount($goods[$key], $this->userData['discount']);

                    }

                }

            }

            if(!$applyDiscount && !empty($this->showColumns($this->goodsTable)['discount'])){

                foreach ($goods as $key => $item){

                    $this->applyGoodsDiscount($goods[$key], ($item['discount'] ?? 0));

                }

            }


            if(!empty($filters)){

                $goodsCountDb = $this->get($this->filtersGoodsTable, [
                    'fields' => [$this->filtersColumn . ' as id', 'COUNT(' . $this->goodsColumn . ') as count'],
                    'where' => [
                        $this->filtersColumn => array_unique(array_column($filters, 'id')),
                        $this->goodsColumn => array_unique(array_column($filters, $this->goodsColumn))
                    ],
                    'group' => $this->filtersColumn
                ]);

                $goodsCount = [];

                if($goodsCountDb){

                    foreach ($goodsCountDb as $item){

                        $goodsCount[$item['id']] = $item;

                    }

                }

                $catalogFilters = [];

                //отсюда разбирать метод
                foreach ($filters as $item){ // в $filters залетают все позиции из фильтров (55шт)

                    $parent = [];

                    $child = [];

                    foreach ($item as $row => $rowValue){ // перебор всех товаров из фильтров (name=>прага)

                        if(strpos($row, 'f_') === 0) { //если на первом месте в ключах стоит f_

                            $name = preg_replace('/^f_/', '', $row); // f_ меняется на '' в $row

                            $parent[$name] = $rowValue; // заполняем массив $parent['name'] значениями из массивов $item

                        }else{

                            $child[$row] = $rowValue; //заполняем массив $child['row'] значениями из массивов $item

                        }

                    }

                    if(isset($goodsCount[$child['id']]['count'])){ //если существует $goodsCount (а он существует и заполнен массивами с [id] и [count])

                        $child['count'] = $goodsCount[$child['id']]['count']; //в массив $child['count'] кладем все из $goodsCount

                    }

                    if(empty($parent['id'])){ // если отсутвует $prarent['id']

                        $parent = $child; //приравниваем первый массив ко второму

                        if(!empty($item[$this->goodsTable . '_value'])){

                            $child['name'] = $item[$this->goodsTable . '_value'];

                            $child['id'] = $child['id'] . '_value_' . $child['name'];

                        }else{

                            continue;

                        }

                    }

                    if(!array_key_exists('show_in_filter', $item) || !empty($item['show_in_filter'])){

                        if(empty($catalogFilters[$parent['id']])){

                            $catalogFilters[$parent['id']] = $parent;

                            $catalogFilters[$parent['id']]['values'] = [];

                        }

                        $catalogFilters[$parent['id']]['values'][$child['id']] = $child;

                    }

                    if(isset($goods[$item[$this->goodsColumn]])){

                        if(empty($goods[$item[$this->goodsColumn]][$this->filtersTable][$parent['id']])){

                            $goods[$item[$this->goodsColumn]][$this->filtersTable][$parent['id']] = $parent;

                            $goods[$item[$this->goodsColumn]][$this->filtersTable][$parent['id']]['values'] = [];

                        }

                        $goods[$item[$this->goodsColumn]][$this->filtersTable][$parent['id']]['values'][$child['id']] = $child;

                    }

                }

                if(!empty($catalogFilters)){

                    foreach ($catalogFilters as $key => $item){

                        if(!empty($item['values'])){

                            if(!preg_grep('/\D/', array_column($item['values'], 'name'))){

                                uasort($catalogFilters[$key]['values'], function($a, $b){

                                    $a['name'] = \AppH::clearNum($a['name']);

                                    $b['name'] = \AppH::clearNum($b['name']);

                                    return $a['name'] === $b['name'] ? 0 : ($a['name'] < $b['name'] ? -1 : 1);

                                });

                            }

                        }

                    }

                }

            }

        }

        return ($single && $goods && count($goods) === 1) ? array_shift($goods) : $goods;

    }

    public function applyGoodsDiscount(&$data, $discount = 0){

        if($discount){

            $data['discount'] = $discount;

            $data['old_price'] = $item['old_price'] ?? $data['price'];

            $data['price'] = $data['old_price'] - ($data['old_price'] / 100 * $data['discount']);

        }

        $this->checkOffersDiscount($data, $discount);

        if(array_key_exists('unit', $data)){

            if(!isset($item['unit'])){

                $data['unit'] = $this->goodsUnits[0];

            }elseif (is_numeric($data['unit'])){

                $data['unit'] = !empty($this->goodsUnits[$data['unit']]) ? $this->goodsUnits[$data['unit']] : $this->goodsUnits[0];

            }

        }

    }

    public function checkOffersDiscount(&$data, $discount = 0){

        if(!empty($data[$this->offersTable]) && !empty($discount)){

            foreach ($data[$this->offersTable] as $key => $item){

                if(!empty($item['price'])){

                    $data[$this->offersTable][$key]['old_price'] = $item['price'];

                    $data[$this->offersTable][$key]['price'] = $item['price'] = $item['price'] - ($item['price'] / 100 * $discount);

                }

            }

        }

    }

    public function setOffers(&$goods, $offers){

        if(!empty($goods) && !empty($offers)){

            $reSortOffers = false;

            foreach ($offers as $item){

                if(array_key_exists('price', $item) && empty($item['price']) && !empty($goods[$item['parent_id']]['price'])){

                    $item['price'] = $goods[$item['parent_id']]['price'];

                    $reSortOffers = true;

                }

                $goods[$item['parent_id']][$this->offersTable][$item['id']] = $item;

            }

            if($reSortOffers){

                foreach ($goods as $key => $item){

                    if(!empty($item[$this->offersTable])){

                        uasort($goods[$key][$this->offersTable], function ($a, $b){

                            $a['price'] = \AppH::clearNum($a['price']);

                            $b['price'] = \AppH::clearNum($b['price']);

                            return $a['price'] === $b['price'] ? 0 : ($a['price'] < $b['price'] ? -1 : 1);

                        });

                    }

                }

            }

        }

    }

    public function setCatalogTables($arr){

        if(!empty($arr)){

            foreach ($arr as $key => $item){

                if(property_exists($this, $key)){

                    $this->$key = $item;

                }

            }

        }

    }

    public function getSimilarGoods($element, $parentRow = 'parent_id', $limit = 12){

        $where['visible'] = 1;

        $where['!id'] = $element['id'];

        $where['<price'] = $element['price'];

        if(array_key_exists($parentRow, $element)){

            $where[$parentRow] = $element[$parentRow];

        }

        $limitGoods = round($limit / 2);

        $resFloor = $this->getGoods([
            'where' => $where,
            'limit' => $limitGoods,
            'order' => 'price',
            'order_direction' => 'desc'
        ]);

        unset($where['<price']);

        if($resFloor){

            $limitGoods = $limit - count($resFloor);

        }else{

            $limitGoods = $limit;

            $resFloor = [];

        }

        $where['>=price'] = $element['price'];

        $resCeil = $this->getGoods([
            'where' => $where,
            'limit' => $limitGoods,
            'order' => 'price',
            'order_direction' => 'asc'
        ]);

        !$resCeil && $resCeil = [];

        return array_merge($resFloor, $resCeil);

    }

}