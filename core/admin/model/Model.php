<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 11.02.2019
 * Time: 16:45
 */

namespace webQAdmin\model;

use webQAdmin\helpers\DataCreatorsModelHelper;
use webQExceptions\DbException;
use webQTraits\AliasImgPathesGeneratorHelper;
use webQAdminSettings\Settings;
use webQModels\BaseModel;

class Model extends BaseModel
{

    use AliasImgPathesGeneratorHelper;
    use DataCreatorsModelHelper;

    public $userData = [];

    public function updateMenuPosition($table, $set = []){

        $row = $set['row'] ?? 'menu_position';

        $end_pos = $set['position'] ?? ($_POST[$row] ?? 1);

        $FIELDS = $set['fields'] ?? ($_POST ?? []);

        $where = $set['where'] ??
            (!empty($FIELDS[$this->showColumns($table)['id_row']]) ?
                [$this->showColumns($table)['id_row'] => $FIELDS[$this->showColumns($table)['id_row']]] : []);

        $parentRow = $set['parent_row'] ?? ($this->showColumns($table)['parent_id'] ? 'parent_id' : false);

        $oldData = $set['old_data'] ?? [];

        $db_where = [];

        if(empty($this->showColumns($table)[$row])) return false;

        if(!empty($where) && !empty($where[$this->showColumns($table)['id_row']])){

            $db_where['!' . $this->showColumns($table)['id_row']] = $where[$this->showColumns($table)['id_row']];

        }

        if($parentRow){

            if($where){

                $DbOldData = $oldData ?: $this->get($table, [
                    'fields' => [$parentRow, $row],
                    'where' => $where,
                    'limit' => 1,
                    'single' => true
                ]);

                if(!$DbOldData){

                    throw new DbException('Ошибка получения данных для сортировки! Исходные данные' . print_r($set, true));

                }

                $start_pos = \WqH::clearNum($DbOldData[$row]);

                if(is_numeric($DbOldData[$parentRow]) && is_numeric($FIELDS[$parentRow])){

                    $FIELDS[$parentRow] = \WqH::clearNum($FIELDS[$parentRow]);

                    $DbOldData[$parentRow] = \WqH::clearNum($DbOldData[$parentRow]);

                }else{

                    if(isset($FIELDS[$parentRow]) && trim(strtolower((string)$FIELDS[$parentRow])) === 'null'){

                        $FIELDS[$parentRow] = null;

                    }

                    if(isset($DbOldData[$parentRow]) && trim(strtolower((string)$DbOldData[$parentRow])) === 'null'){

                        $DbOldData[$parentRow] = null;

                    }

                }

                /*Если перенесли в другую родительскую категорию*/
                if($DbOldData[$parentRow] !== $FIELDS[$parentRow]) {

                    $pos = \WqH::clearNum($this->get($table, [
                        'fields' => ['COUNT(*) as count'],
                        'where' => [$parentRow => $DbOldData[$parentRow]],
                        'single' => true
                    ])['count']);

                    if (!empty($pos)) {

                        $this->edit($table, [
                            'fields' => [$row => "$row - 1"],
                            'where' => [
                                $parentRow => $DbOldData[$parentRow],
                                '>' . $row => $start_pos
                            ],
                            'no_ecran' => $row
                        ]);

                    }

                    $start_pos = $this->get($table, [
                        'fields' => ['COUNT(*) as count'],
                        'where' => [$parentRow => $FIELDS[$parentRow]],
                        'no_concat' => true,
                        'single' => true
                    ])['count'] + ($db_where ? 1 : 0);

                }

            }else{

                $start_pos = $this->get($table, [
                    'fields' => ['COUNT(*) as count'],
                    'where' => [$parentRow => ($FIELDS[$parentRow] ?? null)],
                    'single' => true
                ])['count'] + 1;

            }

            $where_equal = (array_key_exists($parentRow, $FIELDS)) ? $FIELDS[$parentRow] : $oldData[$parentRow];

            $db_where[$parentRow] = $where_equal;

        }else{

            if($where){

                $start_pos = \WqH::clearNum((!empty($oldData[$row]) ? $oldData[$row] : \WqH::clearNum($this->get($table, [
                    'fields' => [$row],
                    'where' => $where,
                    'limit' => 1,
                    'single' => true
                ])[$row])));

            }else{

                $start_pos = $this->get($table, [
                    'fields' => ['COUNT(*) as count'],
                    'single' => true
                ])['count'] + 1;

            }
        }

        $fields = [];

        if($start_pos < $end_pos){

            $fields[$row] = "$row - 1";

            $db_where['<=' . $row] = $end_pos;

            $db_where['>' . $row] = $start_pos;

        }elseif($start_pos > $end_pos){

            $fields[$row] = "$row + 1";

            $db_where['>=' . $row] = $end_pos;

            $db_where['<' . $row] = $start_pos;

        }elseif (!$oldData && $where){

            $fields[$row] = "$row + 1";

            $db_where['>=' . $row] = $end_pos;

        }

        if($fields){

            return $this->edit($table, [
                'fields' => $fields,
                'where'  => $db_where,
                'no_ecran' => $row
            ]);

        }

        return null;

    }

    public function revisionMenuPosition($table, $set = []){

        $row = $set['row'] ?? 'menu_position';

        $where = $set['where'] ?? [];

        $parentRow = $set['parent_row'] ?? ($this->showColumns($table)['parent_id'] ? 'parent_id' : false);

        $idRow = $this->showColumns($table)['id_row'];

        if(!empty($this->showColumns($table)[$row]) && !empty($idRow)){

            $fields = [$idRow, $row];

            $order = [];

            if(!empty($this->showColumns($table)[$parentRow])){

                $fields[] = $parentRow;

                $order[] = $parentRow;

            }

            $order[] = $row;

            $order[] = $idRow . ' DESC';

            $res = $this->get($table, [
                'fields' => $fields,
                'where' => $where,
                'order' => $order
            ]);

            if($res){

                $position = 0;

                $revision = [];

                $parent = false;

                foreach ($res as $key => $item){

                    if($parentRow && array_key_exists($parentRow, $item) && $parent !== $item[$parentRow]){

                        $parent = $item[$parentRow];

                        $position = 0;

                    }

                    $revision[$key][$idRow] = $item[$idRow];

                    $revision[$key][$row] = ++$position;

                }

                if($revision){

                    $this->add($table, [
                        'fields' => $revision,
                        'duplicate' => true
                    ]);

                }

            }

        }

    }

    public function adminSearch($data, $currentTable = false, $page = 1, $qty = null){

        $result = [];

        !$qty && $qty = \Wq::config()->PAGINATION('admin', 'qty') ?? 50;

        $qty_links = \Wq::config()->PAGINATION('admin', 'qty_links') ?? 5;

        $dbTables = $this->showTables();

        if(is_array($page)){

            $pages = $page;

            $page = $pages['page'];

            $qty = $pages['qty'];

            $qty_links = !empty($pages['qty_links']) ? $pages['qty_links'] : $qty_links;

        }

        $data = addslashes($data);

        $arr = preg_split('/,?\s+/u', $data, 0, PREG_SPLIT_NO_EMPTY);

        $searchArr = [];

        $order = [];

        for(;;){

            if(!$arr) break;

            $searchArr[] = implode(' ', $arr);
            unset($arr[count($arr) - 1]);

        }

        $correctCurrentTable = false;

        $temp_tables = Settings::get('projectTables');

        foreach ($temp_tables as $key => $item){

            if(is_numeric($key)){

                $temp_tables[$item] = true;

                unset($temp_tables[$key]);

            }
        }

        foreach($temp_tables as $name => $item){

            if(!in_array($name, $dbTables)) continue;

            $table = $name;

            $seachRows = [];

            $columns = $this->showColumns($table);

            $orderRows = ['name'];

            $fields = [];

            $fields[] = $columns['id_row'] . ' as id';

            if(isset($columns['name'])) $fields['name'] = 'name';

            $fieldName = '';

            foreach($columns as $col => $value){

                if((!isset($fields['name']) || !$fields['name']) && strpos($col, 'name') !== false){

                    if(!$fieldName) $fieldName = 'CASE ';
                    $fieldName .= "WHEN `$col` <> '' THEN `$col` ";

                }

                if(isset($value['Type']) &&
                    (stripos($value['Type'], 'char') !== false ||
                        stripos($value['Type'], 'text') !== false ||
                        stripos($value['Type'], 'float'))){

                    $seachRows[] = $value['Field'];

                }

            }

            if(!empty($fieldName)){

                $fields['name'] = $fieldName . ' END as name';

            }elseif (empty($fields['name'])){

                $fields['name'] = $columns['id_row'] . ' as name';

            }

            $fields[] = "('$table') AS table_name";

            $res = $this->createWhereOrder($seachRows, $searchArr, $orderRows, $table);

            $where = $res['where'];

            !$order && $order = $res['order'];

            if($table === $currentTable) {

                $correctCurrentTable = $table;

            }

            if($where){

                $this->buildUnion($table, [
                    'fields' => $fields,
                    'no_concat' => true,
                    'where' => $where,
                ]);

            }

        }

        if($order){

            $order = ($correctCurrentTable ? "table_name = '" . $correctCurrentTable . "' DESC, " : '') . "(" . implode('+', $order) . ")";

            $order_direction = 'DESC';

        }

        $result = $this->getUnion([
            'pagination' => [
                'page' => $page,
                'qty' => $qty,
                'qty_links' => $qty_links
            ],
            'order' => $order,
            'order_direction' => $order_direction
        ]);

        if($result){

            foreach ($result as $index => $item) {
                if(!$item){
                    unset($result[$index]);
                    continue;
                }

                $result[$index]['name'] .= ' (' . (isset($temp_tables[$item['table_name']]['name']) ? $temp_tables[$item['table_name']]['name'] : $item['table_name']) . ')';

                $result[$index]['alias'] = $this->alias(['edit' => $item['table_name'], $item['id']]);
            }

        }

        return $result ?: [];

    }

    protected function createWhereOrder($seachRows, $searchArr, $orderRows, $table){

        $where = '';

        $order = [];

        if($seachRows){

            $columns = $this->showColumns($table);

            foreach ($seachRows as $row) {

                if(!$where){

                    $where .= '(';

                }

                $where .= '(';

                foreach ($searchArr as $item){

                    $text = '';

                    if(in_array($row, $orderRows))
                        $text = "(`$row` LIKE '%$item%')";

                    if($text && !in_array($text, $order))
                        $order[] = "(`$row` LIKE '%$item%')";

                    if(isset($columns[$row])){

                        $where .= "`$row` LIKE '%$item%' OR ";

                    }

                }

                $where = preg_replace('/\)?\s*or\s*\(?$/i', '', $where);

                $where .= ') OR ';

            }

            if($where) {

                $where = mb_substr($where, 0, -4) . ')';

            }

        }

        return compact('where', 'order');

    }

    public function createColumns($table, $column_name, $column_data){

        $type = !empty($column_data['Type']) ? strtoupper($column_data['Type']) : 'text';

        $null = $column_data === false || empty($column_data['Default']) ? "NULL" : '';

        $query = "ALTER TABLE $table ADD $column_name $type $null";

        $this->query($query, 'u');

        if(!empty($column_data['Field'])){

            $query = "ALTER TABLE $table MODIFY COLUMN $column_name $type AFTER {$column_data['Field']}";

            $this->query($query, 'u');

        }


    }

    public function checkMetaDataTable(){

        if(!in_array('metadata', $this->showTables())){

            $query = "create table metadata
                    (
                        id             int auto_increment primary key,
                        title          varchar(255) null,
                        description    varchar(255) null,
                        keywords       varchar(255) null,
                        name           varchar(255) null,
                        table_name     varchar(255) null,
                        content        text         null,
                        img            varchar(255) null,
                        gallery_img    text         null,
                        short_content  text         null
                    );";

            return $this->query($query, 'u');

        }

        return true;

    }

    public function setTableParameters($table, $parameters){

        if($this->userData && $table && $parameters && ($tableParameters = Settings::get('tableParameters'))){

            $tableExists = true;

            if(!in_array($tableParameters, $this->showTables())){

                $query = "create table $tableParameters
                        (
                            users_id   int          not null,
                            table_name varchar(190) not null,
                            sorting    text         null,
                            constraint table_parameters_pk
                                primary key (users_id, table_name)
                        )";

                $tableExists = $this->query($query, 'u');

            }

            if($tableExists){

                $fields['users_id'] = $this->userData['id'];
                $fields['table_name'] = $table;

                foreach ($parameters as $key => $item){

                    $fields[$key] = $item;

                }

                return $this->add($tableParameters, [
                    'fields' => $fields,
                    'duplicate' => true
                ]);

            }

        }

    }

}