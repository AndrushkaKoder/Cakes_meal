<?php

namespace core\models;


abstract class BaseModelMethods
{
    protected $postNumber;
    protected $linkNumber;
    protected $numberPages;
    protected $page;
    protected $totalCount;

    protected $sqlFunc = ['NOW()', 'RAND()'];

    protected $projectTables;
    protected $tableRows;

    protected $singleRowTables = [];

    protected function createFields($set, $table = false, $join = false){

        if(array_key_exists('fields', $set) && $set['fields'] === null) return '';

        $concat_table = '';
        $alias_table = $table;

        $this->showColumns($table);

        if(empty($set['no_concat'])){

            $arr = $this->createTableAlias($table);

            $concat_table = '`' . $arr['alias'] . '`.';

            $alias_table = $arr['alias'];

        }

        $fields = '';

        $join_structure = false;

        if(($join || isset($set['join_structure']) && $set['join_structure']) && $table){

            $join_structure = true;

            if(isset($this->tableRows[$alias_table]['multi_id_row'])) $set['fields'] = [];

        }

        if(!isset($set['fields']) || !is_array($set['fields']) || !$set['fields']){

            if(!$join){

                if(!empty($set['fields_alias'])){

                    foreach ($this->tableRows[$alias_table] as $key => $item){

                        if($key !== 'id_row' && $key !== 'multi_id_row'){

                            $quoteFlag = false;

                            if(!preg_match('/^(.+)?\s+as\s+(.+)/i', $key)){

                                $key = '`' . $key . '` as `' . $alias_table . '_' . $key . '`';

                                $quoteFlag = true;

                            }

                            if(!preg_match('/\([^()]*\)/', $key)){

                                !$quoteFlag && $key = '`' . $key . '`';

                                $fields .= $concat_table . $key . ',';

                            }

                            else
                                $fields .= $key . ',';

                        }

                    }

                }else{

                    $fields = $concat_table . '*,';

                }

            }else{

                foreach ($this->tableRows[$alias_table] as $key => $item){

                    if($key !== 'id_row' && $key !== 'multi_id_row'){

                        $fields .= $concat_table . '`' . $key . '` as `TABLE' . $alias_table . 'TABLE_' . $key . '`,';

                    }

                }

            }

        }else{

            $id_field = false;

            foreach($set['fields'] as $key => $field){

                if($join_structure && !$id_field && $this->tableRows[$alias_table] === $field){

                    $id_field = true;

                }

                if($field || $set['fields'][$key] === null){

                    if($set['fields'][$key] === null){

                        $fields .= "NULL,";

                        continue;

                    }

                    if($join && $join_structure){

                        if(preg_match('/^(.+)?\s+as\s+(.+)/i', $field, $matches)){

                            $fields .= (!preg_match('/[()]/', $matches[1]) ? $concat_table . '`' . $matches[1] . '`' : $matches[1]) . ' as `TABLE' . $alias_table . 'TABLE_' . $matches[2] . '`,';

                        }else{

                            $fields .= (!preg_match('/[()]/', $field) ? $concat_table . '`' . $field . '`' : $field) . ' as `TABLE' . $alias_table . 'TABLE_' . $field . '`,';

                        }


                    }else{

                        $quoteFlag = false;

                        if(!empty($set['fields_alias'])){

                            if(!preg_match('/^(.+)?\s+as\s+(.+)/i', $field)){

                                $field = '`' . $field . '` as `' . $alias_table . '_' . $field . '`';

                                $quoteFlag = true;

                            }

                        }

                        if(!preg_match('/\([^()]*\)/', $field)){

                            $breakConcat = false;

                            if(preg_match('/^(.+)?\s+as\s+(.+)/i', $field, $matches) && !$quoteFlag){

                                if(!preg_match('/case\s+.+?\s+end/i', $matches[1]))
                                    $matches[1] = '`' . $matches[1] . '`';
                                else
                                    $breakConcat = true;

                                $field = $matches[1] . ' as `' . $matches[2] . '`';

                            }elseif (!$quoteFlag){

                                $field = $field !== '*' ? '`' . $field . '`' : $field;

                            }

                            $fields .= (!$breakConcat ? $concat_table : '') . $field . ',';

                        }

                        else
                            $fields .= $field . ',';

                    }

                }

            }

            if(!$id_field && $join_structure){

                if($join){

                    $fields .= $concat_table . '`' . $this->tableRows[$alias_table]['id_row'] . '` as `TABLE' . $alias_table . 'TABLE_' . $this->tableRows[$alias_table]['id_row'] . '`,';

                }else{

                    $fields .= $concat_table . '`' . $this->tableRows[$alias_table]['id_row'] . '`,';

                }

            }

        }

        return $fields;

    }

    protected function createOrder($set, $table = false){

        if(empty($set['no_concat_order']) && $table)
            $table = '`' . $this->createTableAlias($table)['alias'] . '`.';
        else
            $table = '';

        $order_by = '';

        $set['order'] = !empty($set['order']) ? (array)$set['order'] : [];

        $set['order_direction'] = !empty($set['order_direction']) ? (array)$set['order_direction'] : [];

        if(!empty($set['order'])){

            $order_by = 'ORDER BY ';

            $direct_count = 0;

            foreach ($set['order'] as $order){

                $order = trim($order);

                $order_direction = '';

                if(!empty($set['order_direction'])){

                    if(isset($set['order_direction'][$direct_count])){

                        $order_direction = ' ' . strtoupper($set['order_direction'][$direct_count]);

                        $direct_count++;

                    }else{

                        $order_direction = ' ' . strtoupper($set['order_direction'][$direct_count - 1]);

                    }

                }


                if(in_array($order, $this->sqlFunc)){

                    $order_by .= $order . ',';

                }elseif(is_int($order) || preg_match('/\([^()]*\)/', $order)){

                    $order_by .= $order . $order_direction . ',';

                }else {

                    $orderArr = preg_split('/\s+/', $order, 2, PREG_SPLIT_NO_EMPTY);

                    if(count($orderArr) === 2){

                        $order = $orderArr[0];

                        $order_direction = $orderArr[1];

                    }

                    $order = preg_split('/\./', $order, 0, PREG_SPLIT_NO_EMPTY);

                    $order_by .= (count($order) === 1 ? $table : '') . '`' . implode('`.`', $order) . '` ' . $order_direction . ',';

                }

            }

            $order_by = rtrim($order_by, ',');
        }

        return $order_by;

    }

    protected function createWhere($set, $table = false, $instruction = 'WHERE'){

        if(!empty($set['operand']))
            return $this->createWhereOld($set, $table, $instruction);

        if(empty($set['no_concat_where']) && $table){

            $arr = $this->createTableAlias($table);

            $table = '`' . $arr['alias'] . '`.';

        }else{

            $table = '';

        }

        $where = '';

        if(!empty($set['where'])){

            if(is_string($set['where'])){
                return $instruction . ' ' . trim($set['where']);
            }

            $set['condition'] = !empty($set['condition']) ? (array)$set['condition'] : ['AND'];

            $c_count = 0;

            $where = $instruction;

            foreach ($set['where'] as $key => $item) {

                $where .= ' ';

                if(!empty($set['condition'][$c_count])){

                    $condition = $set['condition'][$c_count];

                    $c_count++;

                }else{

                    $condition = $set['condition'][$c_count - 1];

                }

                $bracketOpen = '';

                $bracketClose = '';

                $key = trim($key);

                preg_match('/^\s*(([\(\)]*\{(.+?)\})|(\W+))/', $key, $matches);

                if(!$matches){

                    $operand = '=';

                }else{

                    $breacketsTest = $matches[0];

                    if (!empty($matches[4]) && ($matches[4] = trim($matches[4]))){//Пришел типовой оператор =, >=, <=, !

                        $matches[4] = preg_replace('/[\(\)]/', '', $matches[4]);

                        if($matches[4] === '!')

                            $operand = '<>';

                        elseif (preg_match('/^=+$/', $matches[4]))

                            $operand = '=';

                        elseif ($matches[4] === '><')

                            $operand = 'BETWEEN';

                        else

                            $operand = $matches[4] ?: '=';

                    }elseif(!empty($matches[3]) && ($matches[3] = trim($matches[3]))){ //пришел символьный оператор

                        $operand = preg_replace('/[\(\)]/', '', $matches[3]);

                    }else{

                        $operand = '=';

                    }

                    if(preg_match('/^[\(\)]+/', $breacketsTest, $matches)){

                        $bracketOpen = preg_replace('/\)/', '', $matches[0]);

                        $bracketClose = preg_replace('/\(/', '', $matches[0]);

                        $bracketOpen && $bracketOpen = ' ' . $bracketOpen;

                        $bracketClose && $bracketClose .= ' ';

                    }

                }

                $rowFunction = '';

                $forceBreakConcatTable = false;

                if(preg_match('/\{([^}]+)\}\s*$/', $key, $funcMatches)){

                    $rowFunction = $funcMatches[1];

                    $key = preg_replace('/\{([^}]+)\}\s*$/', '', $key);

                }

                $key = preg_replace('/^\s*(([\(\)]*\{(.+?)\})|(\W+))/', '', $key);

                if(strtolower($key) === 'exists' &&
                    ((preg_match('/^\s*\(.*?\)\s*$/', $item) || preg_match('/^\s*\(*\s*select\s+/i', $item)))){

                    $operand !== '=' && $key = 'not exists';

                    $operand = '';

                    $forceBreakConcatTable = true;

                    preg_match('/^\s*\(*\s*select\s+/i', $item) && $item = '(' . $item . ')';

                }elseif(strtolower($operand) === 'between'){

                    if(is_string($item)){

                        $item = preg_split('/\s+and\s+/i', $item, 2, PREG_SPLIT_NO_EMPTY);

                    }

                    if(!is_array($item))
                        continue;

                    if(count($item) === 1){

                        $item[1] = $item[0];

                    }

                    $item = $this->clearValues($item[0]) . ' AND ' . $this->clearValues($item[1]);

                }elseif (strtolower($operand) === 'in' || strtolower($operand) === 'not in' || (is_array($item) && ($operand === '=' || $operand === '<>'))){

                    if(is_array($item) && ($operand === '=' || $operand === '<>')){

                        if($operand === '=')
                            $operand = 'IN';
                        else
                            $operand = 'NOT IN';

                    }

                    if(is_string($item) && preg_match('/^\s*\(*\s*select\s+/i', $item)){

                        $item = '(' . $item . ')';

                    }else{

                        if(!$item)
                            continue;

                        if(!is_array($item))
                            $item = preg_split('/\s*,\s*/', $item, 0, PREG_SPLIT_NO_EMPTY);

                        foreach ($item as $k => $v){

                            $item[$k] = $this->clearValues($v);

                        }

                        $item = '(' . implode(',', $item) . ')';

                    }

                }elseif (preg_match('/like/i', $operand)){

                    foreach (explode('%', $operand) as $lt_key => $lt){

                        if(!$lt){

                            if(!$lt_key){

                                $item = '%' . $item;

                            }else{

                                $item .= '%';

                            }
                        }
                    }

                    $operand = preg_replace('/%/', '', $operand);

                    $item = !preg_match('/(rlike)/i', $operand) ? $this->clearValues(str_replace(['_', '%'], ['\_', '\%'], $item)) : "'" . trim($item) . "'";;

                }else{

                    if(is_string($item) && preg_match('/^\s*\(*\s*select\s+/i', $item)){

                        if($operand === '=')
                            $operand = 'IN';
                        else
                            $operand = 'NOT IN';

                        $item = '(' . trim($item) . ')';

                    }elseif ($item === null || strtolower($item) === 'null'){

                        $item = $operand === '=' ? 'IS NULL' : 'IS NOT NULL';

                        $operand = '';

                    }elseif ($item === false){

                        if($operand === '=' || strtolower($operand) === 'in'){

                            $nullOperand = 'IS NULL';

                            $operand = '=';

                            $falseCondition = 'OR';

                        }else{

                            $nullOperand = 'IS NOT NULL';

                            $operand = '<>';

                            $falseCondition = 'AND';

                        }

                        $groupKey = $table . '`' . $key . '`';

                        $item = " '0' $falseCondition $groupKey $operand '' $falseCondition $groupKey $nullOperand)";

                        $table = '(' . $table;

                    }else{

                        $changedItem = false;

                        if(!empty($arr)){

                            foreach ($arr as $tableElementName){

                                $tableElementName = str_replace('-', '\-', preg_quote($tableElementName));

                                if(preg_match('/^\s*' . $tableElementName . '\./', $item)){

                                    $itemArr = preg_split('/\./', $item, 2, PREG_SPLIT_NO_EMPTY);

                                    $item = '`' . implode('`.`', $itemArr) . '`';

                                    $changedItem = true;

                                    break;

                                }

                            }

                        }

                        if(!$changedItem){

                            $item = !preg_match('/(regexp)/i', $operand) ? $this->clearValues($item) : "'" . trim($item) . "'";

                        }

                    }

                }

                if(!$forceBreakConcatTable){

                    $operand = ' ' . $operand;

                    if(!preg_match('/[\(\)]/', $key)){

                        if(preg_match('/\w\.\w/', $key)){

                            $keyArr = preg_split('/\./', $key, 0, PREG_SPLIT_NO_EMPTY);

                            foreach ($keyArr as $k => $keyElement){

                                $keyArr[$k] = !preg_match('/^`[^`]+`$/', $keyElement) ? '`' . $keyElement . '`' : $keyElement;

                            }

                            $key = implode('.', $keyArr);

                        }else{

                            $key = $table . '`' . $key . '`';

                        }

                    }

                }

                $operand .= ' ';

                $tableKey = $rowFunction ? $rowFunction . '(' . $key . ')' : $key;

                $where .= $bracketOpen . $tableKey . $operand . $item . $bracketClose .  ' ' . $condition . ' ';

                $table = ltrim($table, '(');

            }

            $condition && $where = preg_replace('/\s+' . $condition . '\s*$/', '', $where);

        }

        return $where;

    }

    protected function clearValues($value){

        if(is_array($value) || is_object($value))
            $value = json_encode($value);

        return is_int($value) || is_float($value) ? $value : "'" . addslashes(trim($value)) . "'";

    }

    protected function createWhereOld($set, $table = false, $instruction = 'WHERE'){

        if(empty($set['no_concat_where']) && $table){

            $arr = $this->createTableAlias($table);

            $table = $arr['alias'] . '.';

        }else{

            $table = '';

        }

        !empty($set['no_ecran']) && $set['no_ecran'] = (array)$set['no_ecran'];

        $where = '';

        if(!empty($set['where'])){

            if(is_string($set['where'])){
                return $instruction . ' ' . trim($set['where']);
            }

            if(is_array($set['where'])){

                $set['operand'] = (is_array($set['operand']) && !empty($set['operand'])) ? $set['operand'] : ['='];
                $set['condition'] = (is_array($set['condition']) && !empty($set['condition'])) ? $set['condition'] : ['AND'];

                $where = $instruction;

                $o_count = 0;
                $c_count = 0;

                foreach ($set['where'] as $key => $item) {

                    $where .= ' ';

                    if($set['operand'][$o_count]){
                        $operand = $set['operand'][$o_count];
                        $o_count++;
                    }else{
                        $operand = $set['operand'][$o_count - 1];
                    }

                    if($set['condition'][$c_count]){
                        $condition = $set['condition'][$c_count];
                        $c_count++;
                    }else{
                        $condition = $set['condition'][$c_count - 1];
                    }

                    if($operand === 'NO_OPERAND'){

                        $where .= $table . $key . " " . addslashes($item) . " $condition";

                    }elseif($operand === 'IN' || $operand === 'NOT IN'){

                        if(is_string($item) && strpos($item, 'SELECT') === 0){

                            $in_str = $item;

                        }else{

                            if(!$item) continue;

                            if(is_array($item)) $temp_item = $item;
                            else $temp_item = explode(',', $item);

                            $in_str = '';

                            foreach ($temp_item as $v){
                                $in_str .= "'" . addslashes(trim($v)) . "',";
                            }

                        }

                        $where .= $table . $key . ' ' .$operand . ' (' . trim($in_str, ',') . ') ' . $condition;

                    }elseif (strpos($operand, 'LIKE') !== false){

                        $like_template = explode('%', $operand);

                        foreach ($like_template as $lt_key => $lt){
                            if(!$lt){
                                if(!$lt_key){
                                    $item = '%' . $item;
                                }else{
                                    $item .= '%';
                                }
                            }
                        }

                        $where .= $table . $key . ' LIKE ' . "'" . addslashes($item) . "' $condition";

                    }else{

                        if(strpos($item, 'SELECT') === 0){
                            $where .= $table . $key . $operand . '(' . $item . ") $condition";
                        }elseif ($item === null || mb_strtolower($item) === 'null'){

                            if($operand === '=') $where .= $table . $key . " IS NULL " . $condition;
                            else $where .= $table . $key . " IS NOT NULL " . $condition;

                        }else{
                            if(!empty($set['no_ecran']) && in_array($key, $set['no_ecran']))
                                $where .= $table . $key . $operand . addslashes($item) . " $condition";
                            else
                                $where .= $table . $key . $operand . "'" . addslashes($item) . "' $condition";
                        }

                    }

                }

                $condition && $where = substr($where, 0, strrpos($where, $condition));

            }

        }

        return $where;

    }

    protected function createJoin($set, $table, $new_where = false){

        $fields = '';
        $join = '';
        $where = '';
        $order = '';
        $tables = [];

        if(!empty($set['join'])){

            $join_table = $table;

            foreach ($set['join'] as $key => $item){

                if(is_int($key)){
                    if(!$item['table']) continue;
                    else $key = $item['table'];
                }

                $tablesArr = $this->createTableAlias($key);

                $concat_table = $tablesArr['alias'];

                $tables[] = $tablesArr['table'] === $tablesArr['alias'] ? '`' . $tablesArr['table'] . '`' :
                    '`' . $tablesArr['table'] . '` `' . $tablesArr['alias'] . '`';

                if(isset($item['single'])) $this->singleRowTables[] = $concat_table;

                if($join) $join .= ' ';

                if(!empty($item['on'])){

                    if(isset($item['on']['fields']) && is_array($item['on']['fields']) && count($item['on']['fields'])){

                        $join_fields = $item['on']['fields'];

                    }elseif(isset($item['on']) && is_array($item['on'])){

                        $join_fields = $item['on'];

                    }else{

                        continue;

                    }

                    if(count($join_fields) === 1 && !is_numeric($joinKey = key($join_fields))){

                        $join_fields[0] = $joinKey;

                        $join_fields[1] = $join_fields[$joinKey];

                        unset($join_fields[$joinKey]);

                    }elseif (count($join_fields) !== 2) continue;

                    if(empty($item['type'])) $join .= 'LEFT JOIN ';
                    else $join .= trim(strtoupper($item['type'])). ' JOIN ';

                    $keyArr = preg_split('/\s+/', $key, 2, PREG_SPLIT_NO_EMPTY);

                    foreach ($keyArr as $k => $v){

                        $keyArr[$k] = '`' . $v . '`';

                    }

                    $join .= implode(' ', $keyArr) . ' ON ';

//                    if($item['on']['table']) $join .= $item['on']['table'];
//                    else $join .= $join_table;

                    if(!empty($item['on']['table'])) $join_temp_table = $item['on']['table'];
                        else $join_temp_table = $join_table;


                    $join .= '`' . $this->createTableAlias($join_temp_table)['alias'] . '`';

                    $join .= '.`' . $join_fields[0] . '`=`' . $concat_table . '`.`' .$join_fields[1] . '`';

                    $join_table = $key;

                    if($new_where){

                        if(!empty($item['where'])){
                            $new_where = false;
                        }

                        $group_condition = 'WHERE';

                    }else{
                        $group_condition = ' ' . (!empty($item['group_condition']) ? strtoupper($item['group_condition']) : 'AND');
                    }

                    $fields .= $this->createFields($item, $key, ($set['join_structure'] ?? null));
                    $where .= $this->createWhere($item, $key, $group_condition);
                    $order .= $order ? preg_replace('/^\s*order\s+by/i', ',', $this->createOrder($item, $key)) : $this->createOrder($item, $key);

                }

            }

        }

        return compact('fields', 'join', 'where', 'order', 'tables');

    }

    protected function createInsert($table, $fields, $files, $except, $duplicate = false){

        $insert_arr = [];

        $insert_arr['fields'] = '(';

        $insert_arr['values'] = '';

        $insert_arr['duplicate'] = $duplicate ? 'ON DUPLICATE KEY UPDATE ' : '';

        $array_type = array_keys($fields)[0];

        if(is_int($array_type)){

            $check_fields = false;

            $maxCountElement = 0;

            $fieldsKey = null;

            foreach ($fields as $key => $item){

                if(!isset($fieldsKey))
                    $fieldsKey = $key;

                $count = count($item);

                if($count > $maxCountElement){

                    $maxCountElement = $count;

                    $fieldsKey = $key;

                }

            }

            foreach ($fields[$fieldsKey] as $row => $value){

                if(($except && in_array($row, $except)) || !isset($this->showColumns($table)[$row])){

                    unset($fields[$fieldsKey][$row]);

                }

            }

            $currentField = $fields[$fieldsKey];

            unset($fields[$fieldsKey]);

            array_unshift($fields, $currentField);

            foreach ($fields as $i => $item){

                $insert_arr['values'] .= '(';

                foreach ($currentField as $row => $value){

                    if(($except && in_array($row, $except)) || !isset($this->showColumns($table)[$row])) continue;

                    if(!$check_fields){

                        $insert_arr['fields'] .= '`' . $row . '`,';

                        if($duplicate)
                            $insert_arr['duplicate'] .= '`' . $row . '`=VALUES(`' . $row . '`),';

                    }

                    $value = $fields[$i][$row] ?? null;

                    if(is_array($value)) $value = json_encode($value);

                    if(in_array($value, $this->sqlFunc)){

                        $insert_arr['values'] .= $value . ',';

                    }elseif ($value == 'NULL' || $value === NULL){

                        $insert_arr['values'] .= "NULL" . ',';

                    }else{

                        $insert_arr['values'] .= "'" . addslashes($value) . "',";
                    }

                }

                $insert_arr['values'] = rtrim($insert_arr['values'], ',') . '),';

                if(!$check_fields)
                    $check_fields = true;

            }

        }else{

            $insert_arr['values'] = '(';

            if($fields){

                foreach ($fields as $row => $value){

                    if(($except && in_array($row, $except)) || !isset($this->showColumns($table)[$row])) continue;

                    $insert_arr['fields'] .= '`'. $row . '`,';

                    if($duplicate)
                        $insert_arr['duplicate'] .= '`' . $row . '`=VALUES(`' . $row . '`),';

                    if(is_array($value)) $value = json_encode($value);

                    if(in_array($value, $this->sqlFunc)){

                        $insert_arr['values'] .= $value . ',';

                    }elseif ($value == 'NULL' || $value === NULL){

                        $insert_arr['values'] .= "NULL" . ',';

                    }else{

                        $insert_arr['values'] .= "'" . addslashes($value) . "',";
                    }

                }

            }

            if($files){

                foreach ($files as $row => $file){

                    $insert_arr['fields'] .= '`' . $row . '`,';

                    if(is_array($file)) $insert_arr['values'] .= "'" . addslashes(json_encode($file)) . "',";
                        else $insert_arr['values'] .= "'" . addslashes($file) . "',";

                    if($duplicate)
                        $insert_arr['duplicate'] .= '`' . $row . '`=VALUES(`' . $row . '`),';

                }

            }

            $insert_arr['values'] = rtrim($insert_arr['values'], ',') . ')';

        }

        if($duplicate) $insert_arr['duplicate'] = rtrim($insert_arr['duplicate'], ',');

        $insert_arr['fields'] = rtrim($insert_arr['fields'], ',') . ')';

        $insert_arr['values'] = rtrim($insert_arr['values'], ',');

        return $insert_arr;

    }

    protected function createUpdate($table, $fields, $files, $except, $no_ecran = []){

        $update = '';

        $no_ecran && $no_ecran = (array)$no_ecran;

        if($fields){

            foreach($fields as $row => $value){

                if(($except && in_array($row, $except)) || !isset($this->showColumns($table)[$row])) continue;

                $update .= '`' . $row . '`=';

                if(is_array($value)) $value = json_encode($value);

                if(in_array($value, $this->sqlFunc) || in_array($row, $no_ecran)){
                    $update .= $value . ',';
                }elseif ($value === NULL || strtolower($value) === 'null'){
                    $update .= "NULL" . ',';
                }else{
                    $update .= "'" . addslashes($value) . "',";
                }

            }
        }

        if($files){

            foreach ($files as $row => $file){

                $update .= '`' . $row . '`=';

                if(is_array($file)) $update .= "'" . addslashes(json_encode($file)) . "',";
                else $update .= "'" . addslashes($file) . "',";

            }

        }

        return rtrim($update, ',');

    }

    protected function joinStructure($res, $table){

        $join_arr = [];

        $id_row = $this->tableRows[$this->createTableAlias($table)['alias']]['id_row'];

        foreach ($res as $value){

            if($value){

                if(!isset($join_arr[$value[$id_row]])) $join_arr[$value[$id_row]] = [];

                foreach($value as $key => $item){

                    if(preg_match('/TABLE(.+)?TABLE/u', $key, $matches)){

                        $table_name_normal = str_replace('_AS_', ' ', $matches[1]);

                        if(!isset($this->tableRows[$table_name_normal]['multi_id_row'])){

                            $join_id_row = $value[$matches[0] . '_' . $this->tableRows[$table_name_normal]['id_row']];

                        }else{

                            $join_id_row = '';

                            foreach ($this->tableRows[$table_name_normal]['multi_id_row'] as $multi){

                                $join_id_row .= $value[$matches[0] . '_' . $multi];

                            }

                        }

                        $row = preg_replace('/TABLE(.+)TABLE_/u', '', $key);

                        if($join_id_row && !isset($join_arr[$value[$id_row]]['join'][$table_name_normal][$join_id_row][$row])){

                            $join_arr[$value[$id_row]]['join'][$table_name_normal][$join_id_row][$row] = $item;

                        }

                        continue;

                    }

                    $join_arr[$value[$id_row]][$key] = $item;

                }

            }

        }

        return $join_arr;

    }

    protected function getTotalCount($table, $where, $rows = '*'){

        return $this->query("SELECT COUNT($rows) AS count FROM $table $where")[0]['count'];

    }

    public function getPagination(){

        if (!$this->numberPages || $this->numberPages == 1 || $this->page > $this->numberPages) {
            return false;
        }

        $res = [];

        if ($this->page != 1) {
            $res['first'] = 1;
            $res['back'] = $this->page - 1;
        }

        if($this->page > $this->linkNumber + 1){
            for($i = $this->page - $this->linkNumber; $i < $this->page; $i++){
                $res['previous'][] = $i;
            }
        }else{
            for($i = 1; $i < $this->page; $i++){
                $res['previous'][] = $i;
            }
        }

        $res['current'] = $this->page;

        if($this->page + $this->linkNumber < $this->numberPages){
            for($i = $this->page + 1; $i <= $this->page + $this->linkNumber; $i++){
                $res['next'][] = $i;
            }
        }else{
            for($i = $this->page + 1; $i <= $this->numberPages; $i++){
                $res['next'][] = $i;
            }
        }

        if ($this->page != $this->numberPages) {
            $res['forward'] = $this->page + 1;
            $res['last'] = (int)$this->numberPages;
        }

        if($this->totalCount){
            $res['totalCount'] = (int)$this->totalCount;
        }

        return $res;

    }

    public function getTotalCountProperty(){

        return $this->totalCount;

    }

    protected function createTableAlias($table){

        $arr = [];

        if(preg_match('/\s+/i', $table)){

            //$table = preg_replace('/\s{2,}/i', ' ', $table);

            $table_name = preg_split('/\s+/', $table, 0, PREG_SPLIT_NO_EMPTY);

            $arr['alias'] = trim($table_name[1]);

            $arr['table'] = trim($table_name[0]);

        }else{

            $arr['alias'] = $arr['table'] = $table;

        }

        return $arr;

    }

}