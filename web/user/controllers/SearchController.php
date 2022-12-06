<?php

namespace web\user\controllers;

use core\admin\expansion\VisitorsExpansion;
use core\user\model\Model;

class SearchController extends BaseUser
{

    private $searchTables = ['goods'];

    private $searchRows = ['name', 'content'];

    private $orderRows = ['name', 'content'];

    private $fields = [];

    private $returnQuery = false;

    private $noPagination = false;

    public $callback = null;

    protected function actionInput()
    {



        $result = $this->searchData();

        $data = $result['data'] ?? null;

        $pages = $result['pages'] ?? null;

        $totalCount = $result['totalCount'] ?? 0;

        return compact('data', 'pages', 'totalCount');

    }

    public function searchData($search = ''){

        !$search && $search = $_GET['search'] ?? null;

        if(!$search || !$this->searchTables || !$this->searchRows){

            if($this->returnQuery){

                return false;

            }

            \AppH::redirect();

        }

        !$this->model && $this->model = \web\user\models\Model::instance();

        $search = trim(preg_replace('/[^\w\-\s]/u', '', $search));

        $arr = preg_split('/\s+/', $search, 0, PREG_SPLIT_NO_EMPTY);

        if(count($arr) >= 2){

            $arr[0] .= ' ' . $arr[1];

            unset($arr[1]);

            $arr = array_values($arr);

        }

        $searchArr = [];

        for(;;){

            if(!$arr){

                break;

            }

            $searchArr[] = implode(' ', $arr);

            array_pop($arr);

        }

        $order = [];

        $orderByGoodsTableName = false;

        foreach ($this->searchTables as $table){

            $res = $this->createWhereOrder($searchArr, $table);

            $where = $res['where'];

            !$order && $order = $res['order'];

            if($where){

                $fields = ['*', "('$table') AS table_name"];

                if($this->fields){

                    $fields = $this->fields[$table] ?? $this->fields;

                }elseif(!empty($this->model->goodsTable) && $this->model->goodsTable === $table){

                    $orderByGoodsTableName = true;

                }

                $this->model->buildUnion($table, [
                    'fields' => $fields,
                    'no_concat' => true,
                    'where' => $where
                ]);

            }

        }

        $dbOrder = '';

        if($order){

            if($orderByGoodsTableName){

                $dbOrder = "table_name = '{$this->model->goodsTable}' DESC";

            }

            $firstOrder = preg_replace('/[\(\)]]/', '', $order[0]);

            $dbOrder .= ($dbOrder ? ', ' : '') . "IF($firstOrder, 1, 0) DESC, (" . implode('+', $order) . ") DESC";

            if(!empty($this->model->goodsTable) && in_array($this->model->goodsTable, $this->searchTables) &&
                !empty($this->model->showColumns($this->model->goodsTable)['price'])){

                $dbOrder .= ', IF(price > 0, 1, 0) DESC, price';

            }

        }

        if(!$this->noPagination){

            $page = !empty($_GET['page']) ? \AppH::clearStr($_GET['page']) : 0;

            if(!$this->returnQuery && !$page){

                $page = 1;

            }

        }


        $data = $this->model->getUnion([
            'pagination' => $page ?? null,
            'order' => $dbOrder,
            'return_query' => $this->returnQuery
        ]);

        $pages = !$this->returnQuery ? $this->model->getPagination() : [];

        if($data){

            if($this->callback && is_callable($this->callback)){

                $calback = $this->callback;

                $this->callback = null;

                $calback($data);

            }

        }

        return compact('data', 'pages');


    }

    protected function createWhereOrder($searchArr, $table){

        $where = '';

        $order = [];

        $columns = $this->model->showColumns($table);

        foreach ($this->searchRows as $row){

            if(!$where){

                if(!empty($columns['visible'])){

                    $where .= 'visible = 1 AND (';

                }else{

                    $where .= '(';

                }

            }

            $where .= '(';

            foreach ($searchArr as $item){

                $orderItem = '';

                if(in_array($row, $this->orderRows)){

                    $orderItem = "($row LIKE '%$item%')";

                }

                if($orderItem && !in_array($orderItem, $order)){

                    $order[] = $orderItem;

                }

                if(isset($columns[$row])){

                    $where .= "$row LIKE '%$item%' OR ";

                }

            }

            $where = preg_replace('/\)?\s*or\s*\(?$/i', '', $where);

            $where .= ') OR ';

        }

        if($where){

            $where = preg_replace('/\s+or\s+$/i', '', $where) . ')';

        }

        return compact('where', 'order');

    }

    public function setSearchParameters(array $parameters) : void{

        foreach ($parameters as $key => $item){

            if(property_exists($this, $key)){

                $this->$key = $item;

            }

        }

    }

}