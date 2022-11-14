<?php

namespace core\models;

use core\exceptions\DbException;
use core\traites\Singleton;

abstract class BaseModel extends BaseModelMethods
{

    use Singleton;

    protected $db;
    protected $bufferingErrors = false;
    protected $union = [];
    protected $driver;

    protected function setStorage(){

        $thisName = (new \ReflectionClass($this))->getName();

        foreach ($this as $name => $value){

            $propertyClassName = (new \ReflectionProperty($this, $name))->getDeclaringClass()->getName();

            if($thisName !== $propertyClassName){

                if(property_exists(DBStorage::instance(), $name)){

                    $this->$name = &DBStorage::instance()->$name;

                }else{

                    DBStorage::instance()->$name = &$this->$name;

                }

            }

        }

    }

    public function connect($reConnect = false)
    {

        !$reConnect && $this->setStorage();

        $connectMethod = \App::DB('driver').'Connection';

        $this->db = DbConnection::$connectMethod();

    }

    /**
     * @param $query
     * @param string $crud = r - SELECT / c - INSERT / u - UPDATE / d - DELETE
     * @param bool $return_id
     * @return array|bool|mixed
     * @throws DbException
     */

    final public function setBufferingMode($mode){

        $this->bufferingErrors = $mode;

    }

    public function query($query, $crud = 'r', $return_id = false, ?array $parameters = [])
    {

        $connectMethod = \App::DB('driver').'Query';

        return  DbConnection::$connectMethod($query, $crud, $return_id, $parameters);


    }

    /**
     * @param $table - Таблици базы данных
     * @param array $set
     * 'fields' => ['id', 'name'],
     * 'fields_alias' => true - Создает псевдоним полей при наличии псевдонима таблицы или в формате <таблица>_<имя поля>
     * 'no_concat' => false/true Если true не присоединять имя таблицы к fields
     * 'no_concat_where' => false/true Если true не присоединять имя таблицы к where
     * 'no_concat_order' => false/true Если true не присоединять имя таблицы к order
     * 'where' => ['fio' => 'smirnova', 'name' => 'Masha', 'surname' => 'Sergeevna']
     * 'operand' => ['=', '<>']
     * 'condition' => ['AND']
     * 'order' => ['fio', 'name', 'surname']
     * 'order_direction' => ['ASC', 'DESC']
     * 'group_condition' => 'AND'
     * 'limit' => '1'
     * single => true - возвращает один результат и сразу в ассоциативном массиве
     *   'join' => [
     *   [
     *      'table' => 'join_table1',
     *      'fields' => ['id as j_id', 'name as j_name'],
     *      'type' => 'left',
     *      'where' => ['name' => 'sasha'],
     *      'operand' => ['='],
     *      'condition' => ['OR'],
     *      'on' => ['id', 'parent_id'],
     *      'group_condition' => 'AND'
     *   ],
     * 'join_table1' =>[
     *      'fields' => ['id as j2_id', 'name as j2_name'],
     *      'type' => 'left',
     *      'where' => ['name' => 'sasha'],
     *      'operand' => ['<>'],
     *      'condition' => ['AND'],
     *      'on' => [
     *          'table' => 'teachers',
     *          'fields' => ['id', 'parent_id']
     *      ]
     *   ]
     *]
     */

    public function get($table, $set = []){

        $fields = $this->createFields($set, $table);

        $order = $this->createOrder($set, $table);

        $paginationWhere = $where = $this->createWhere($set, $table);

        $distinct = isset($set['distinct']) && $set['distinct'] ? 'DISTINCT' : '';

        $group = isset($set['group']) && $set['group'] ? 'GROUP BY ' . $set['group'] : '';

        if(!$where) $new_where = true;
        else $new_where = false;

        $join_arr = $this->createJoin($set, $table, $new_where);

        $fields .= $join_arr['fields'];

        $join = $join_arr['join'];

        if(isset($set['group_condition']) && $where && $join_arr['where']){

            $where .= ' ' . preg_replace('/^\s*[a-z]{2,}\s+/i', " {$set['group_condition']} ", $join_arr['where']);

        }else{

            $where .= ' ' . $join_arr['where'];

        }

        $order .= $order ? preg_replace('/order\s+by/i', ',', $join_arr['order']) : $join_arr['order'];

        $fields = preg_replace('/,\s*$/', '', $fields);

        $limit = !empty($set['limit']) ? 'LIMIT ' . $set['limit'] : '';

        $tableArr = $this->createTableAlias($table);

        if($tableArr['table'] !== $tableArr['alias']){

            $dbTable = '`' . $tableArr['table'] . '` `' . $tableArr['alias'] . '`';

        }else{

            $dbTable = '`' . $table . '`';

        }

        $this->createPagination($set, $dbTable, $paginationWhere, $limit);

        $query = "SELECT $distinct $fields FROM $dbTable $join $where $group $order $limit";

        if(isset($set['return_query']) && $set['return_query'])
            return $query;

        $res = $this->query($query);

        if(isset($set['join_structure']) && $set['join_structure'] && $res){

            $res = $this->joinStructure($res, $table);

            if($this->singleRowTables){

                foreach ($res as $key => $item){

                    if(isset($item['join'])){

                        foreach ($item['join'] as $k => $v){

                            if(in_array($k, $this->singleRowTables) && count($v) === 1){

                                $res[$key]['join'][$k] = $v[key($v)];

                            }

                        }

                    }

                }

            }

        }

        if($res && !empty($set['single']) && count($res) === 1) $res = $res[key($res)];

        return $res;

    }

    protected function createPagination($set, $table, $where, &$limit = ''){

        if(isset($set['pagination']) && $set['pagination']){

            $this->postNumber = isset($set['pagination']['qty']) ? (int)$set['pagination']['qty'] : \App::PAGINATION('user', 'QTY');

            $this->linkNumber = isset($set['pagination']['qty_links']) ? (int)$set['pagination']['qty_links'] : \App::PAGINATION('user', 'QTY_LINKS');

            $this->page = !is_array($set['pagination']) ? (int)$set['pagination'] : (int)$set['pagination']['page'];

            if($this->page > 0 && $this->postNumber > 0){

                $this->totalCount = $this->getTotalCount($table, $where, (!empty($set['group']) ? 'distinct ' . preg_replace('/\s+(asc|desc)(\W|$)/i', '$2', $set['group']) : '*'));

                $this->numberPages = (int)ceil($this->totalCount / $this->postNumber);

                $limit = 'LIMIT ' . ($this->page - 1) * $this->postNumber . ',' . $this->postNumber;

            }

        }

    }

    public function buildUnion($table, $set = []){

        if(array_key_exists('fields', $set) && $set['fields'] === null) return $this;

        if(!empty($set['fields']) && is_array($set['fields'])){

            $key = array_search('*', $set['fields']);

        }

        if(!isset($set['fields']) || !$set['fields'] || (isset($key) && $key !== false)){

            if(!isset($set['fields'])){

                $set['fields'] = [];

            }elseif (isset($key)){

                unset($set['fields'][$key]);

            }

            $columns = $this->showColumns($table);

            unset($columns['id_row'], $columns['multi_id_row']);

            foreach ($columns as $row => $item){

                if(!in_array($row, $set['fields'])){

                    $set['fields'][] = $row;

                }

            }

        }

        $this->union[$table] = $set;

        $this->union[$table]['return_query'] = true;

        return $this;

    }

    /**
     * @param array $set
     * @return array|bool
     * @throws DbException
     *
     * type = 'all';
     * 'pagination' => 4,
     * 'order' => ["((name LIKE '%тов%')+(content LIKE '%тов%'))"],
     * 'order_direction' => ['DESC']
     */

    public function getUnion($set = []){

        if(!$this->union) return false;

        $unionType = ' UNION ' . (isset($set['type']) ? strtoupper($set['type']) . ' ' : '');

        $maxCount = 0;

        $maxTableCount = '';

        foreach($this->union as $key => $item){

            $count = count($item['fields']);

            $joinFields = '';

            if(isset($item['join'])){

                foreach($item['join'] as $table => $data){

                    if(array_key_exists('fields', $data) && $data['fields']){

                        $count += count((array)$data['fields']);

                        $joinFields = $table;

                    }elseif(!array_key_exists('fields', $data) || (!$data['fields'] || $data['fields'] !== null)){

                        $columns = $this->showColumns($table);

                        unset($columns['id_row'], $columns['multi_id_row']);

                        $count += count($columns);

                        foreach($columns as $field => $value){

                            $this->union[$key]['join'][$table]['fields'][] = $field;

                        }

                        $joinFields = $table;

                    }

                }

            }else{

                $this->union[$key]['no_concat'] = true;

            }

            if($count > $maxCount || $count === $maxCount && $joinFields){

                $maxCount = $count;

                $maxTableCount = $key;

            }

            $this->union[$key]['lastJoinTable'] = $joinFields;

            $this->union[$key]['countFields'] = $count;

        }

        $query = '';

        if($maxCount && $maxTableCount){

            $query .= '(' .  $this->get($maxTableCount, $this->union[$maxTableCount]) . ')';

            unset($this->union[$maxTableCount]);

        }

        foreach ($this->union as $key => $item){

            if(isset($item['countFields']) && $item['countFields'] < $maxCount){

                for($i = 0; $i < $maxCount - $item['countFields']; $i++){

                    if($item['lastJoinTable'])
                        $item['join'][$item['lastJoinTable']]['fields'][] = null;

                    else $item['fields'][] = null;

                }

            }

            $subQuery = $this->get($key, $item);

            $subQuery && $query && $query .= $unionType . '(' . $subQuery . ')';

        }

        $order = $this->createOrder($set);

        $limit = !empty($set['limit']) ? 'LIMIT ' . $set['limit'] : '';

        $this->createPagination($set, "($query)", ' as tmp_table', $limit);

        $query .= " $order $limit";

        if(!empty($set['return_query'])){

            return $query;

        }

        $this->union = [];

        $res = $this->query(trim($query));

        method_exists($this, 'checkMultiLanguage') && $sub = $this->checkMultiLanguage();

        if(!empty($sub) && method_exists($this, 'createMultiLanguageResult')){

            return $this->createMultiLanguageResult($res, $sub);

        }

        return $res;

    }


    /**
     * @param $table - таблица для вставки данных
     * @param array $set - массив параметров:
     * fields => [поле => значение]; если не указан, то обрабатывается $_POST[поле => значение]
     * разрешена передача например NOW() в качестве Mysql функции обычно строкой
     * files => [поле => значение]; можно подать массив вида [поле => [массив значений]]
     * except => ['исключение 1', 'исключение 2'] - исключает данные элементы массива из добавления в запрос
     * return_id => true|false - возвращать или нет идентификатор вставленной записи
     * duplicate => tru/false - если true = возвращает инструкцию ON DUPLICATE KEY UPDATE
     * @return mixed
     */

    public function add($table, $set = [], $callback = ''){

        $set['fields'] = $set['fields'] ?? $_POST;

        $set['files'] = (!empty($set['files']) && is_array($set['files'])) ? $set['files'] : false;

        if(!$set['fields'] && !$set['files']) return false;

        $set['return_id'] = !empty($set['return_id']);

        $set['except'] = (!empty($set['except']) && is_array($set['except'])) ? $set['except'] : false;

        $set['duplicate'] = !empty($set['duplicate']);

        $insert_arr = $this->createInsert($table, $set['fields'], $set['files'], $set['except'], $set['duplicate']);

        $query = "INSERT INTO `$table` {$insert_arr['fields']} VALUES {$insert_arr['values']} {$insert_arr['duplicate']}";

        if($callback) $callback($query);

        $res = $this->query($query, 'c', $set['return_id']);

        if($res){

            $this->changeCacheData($table);

        }

        return $res;

    }

    public function edit($table, $set = []){

        $set['fields'] = $set['fields'] ?? $_POST;

        $set['files'] = (!empty($set['files']) && is_array($set['files'])) ? $set['files'] : false;

        $set['no_ecran'] = !empty($set['no_ecran']) ? $set['no_ecran'] : [];

        if(!$set['fields'] && !$set['files']) return false;

        $set['except'] = (!empty($set['except']) && is_array($set['except'])) ? $set['except'] : false;

        if(empty($set['all_rows'])){

            if(!empty($set['where'])){

                $where = $this->createWhere($set);

            }else{

                $columns = $this->showColumns($table);

                if(!$columns) return false;

                if(!empty($columns['id_row']) && !empty($set['fields'][$columns['id_row']])){

                    $where = 'WHERE `' . $columns['id_row'] . '`=' . $set['fields'][$columns['id_row']];
                    unset($set['fields'][$columns['id_row']]);

                }

            }

        }

        $update = $this->createUpdate($table, $set['fields'], $set['files'], $set['except'], $set['no_ecran']);

        $query = "UPDATE `$table` SET $update $where";

        $res = $this->query($query, 'u');

        if($res){

            $this->changeCacheData($table);

        }

        return $res;

    }

    public function checkSortingData(string $table, array $fields) : array{



    }

    /**
     * @param $table - Таблици базы данных
     * @param array $set
     * 'fields' => ['id', 'name']
     * 'where' => ['fio' => 'smirnova', 'name' => 'Masha', 'surname' => 'Sergeevna']
     * 'operand' => ['=', '<>']
     * 'condition' => ['AND']
     *   'join' => [
     *   [
     *      'table' => 'join_table1',
     *      'fields' => ['id as j_id', 'name as j_name'],
     *      'type' => 'left',
     *      'where' => ['name' => 'sasha'],
     *      'operand' => ['='],
     *      'condition' => ['OR'],
     *      'on' => ['id', 'parent_id'],
     *      'group_condition' => 'AND'
     *   ],
     * 'join_table1' =>[
     *      'fields' => ['id as j2_id', 'name as j2_name'],
     *      'type' => 'left',
     *      'where' => ['name' => 'sasha'],
     *      'operand' => ['<>'],
     *      'condition' => ['AND'],
     *      'on' => [
     *          'table' => 'teachers',
     *          'fields' => ['id', 'parent_id']
     *      ]
     *   ]
     *]
     */

    public function delete($table, $set = []){

        $table = trim($table);

        $where = $this->createWhere($set, $table);

        $limit = !empty($set['limit']) ? 'LIMIT ' . $set['limit'] : '';

        $columns = $this->showColumns($table);

        if(!$columns) return false;

        if(!empty($set['fields']) && is_array($set['fields'])){

            if($columns['id_row']){
                $key = array_search($columns['id_row'], $set['fields']);
                if($key !== false) unset($set['fields'][$key]);
            }

            $fields = [];

            foreach($set['fields'] as $field){
                $fields[$field] = $columns[$field]['Default'];
            }

            $update = $this->createUpdate($table, $fields, false, false);

            $query = "UPDATE `$table` SET $update $where";

        }else{

            $join_arr = $this->createJoin($set, $table);
            $join = $join_arr['join'];
            $join_tables = $join_arr['tables'];

            $join_tables = $join_tables ? ',' . implode(',', $join_tables) : '';

            $fromTable = !$limit ? '`' . $table . '`' : '';

            $query = 'DELETE ' . $fromTable . ' ' . $join_tables . ' FROM `' . $table . '` ' . $join . ' ' . $where . ' ' . $limit;

        }

        $res = $this->query($query, 'u');

        if($res){

            $this->changeCacheData($table);

        }

        return $res;

    }

    final public function showColumns($table, $clear = false){

        if($clear || !isset($this->tableRows[$table]) || !$this->tableRows[$table]){

            $check_table = $this->createTableAlias($table);

            if(isset($this->tableRows[$check_table['table']]) &&
                $this->tableRows[$check_table['table']] &&
                !$clear){

                return $this->tableRows[$check_table['alias']] = $this->tableRows[$check_table['table']];

            }

            $query = "SHOW COLUMNS FROM `{$check_table['table']}`";
            $res = $this->query($query);

            $this->tableRows[$check_table['table']] = [];

            if($res){

                foreach ($res as $row){

                    $this->tableRows[$check_table['table']][$row['Field']] = $row;

                    if($row['Key'] === 'PRI'){

                        if(!isset($this->tableRows[$check_table['table']]['id_row'])){

                            $this->tableRows[$check_table['table']]['id_row'] = $row['Field'];

                        }else{

                            if(!isset($this->tableRows[$check_table['table']]['multi_id_row']))
                                $this->tableRows[$check_table['table']]['multi_id_row'][] = $this->tableRows[$check_table['table']]['id_row'];

                            $this->tableRows[$check_table['table']]['multi_id_row'][] = $row['Field'];

                        }

                    }
                }

            }

        }

        if(isset($check_table) && $check_table['table'] !== $check_table['alias']){

            return $this->tableRows[$check_table['alias']] = $this->tableRows[$check_table['table']];

        }

        return $this->tableRows[$table];
    }

    final public function showTables(){

        if($this->projectTables) return $this->projectTables;

        $query = 'SHOW TABLES';

        $tables = $this->query($query);

        $this->projectTables = [];

        if($tables){

            foreach($tables as $table){

                $this->projectTables[] = reset($table);

            }

        }

        return $this->projectTables;

    }

    private function changeCacheData($table){

        if($table !== 'cached_tables'){

            $this->setCache($table);

            $this->edit('cached_tables', [
                'fields' => ['date' => 'NOW()'],
                'where' => ['name' => $table]
            ]);

        }

    }

    protected function setCache($table){

        if($this->checkCacheTable()){

            if(!$this->get('cached_tables', [
                'where' => ['name' => $table],
                'no_check_credentials' => true
            ])){

                $this->add('cached_tables', [
                    'fields' => [
                        'name' => $table,
                        'date' => 'NOW()',
                    ]
                ]);

            }

            if($this->checkGeneralSettings()){

                if (!array_key_exists('cache_time', $this->showColumns('general_settings'))){

                    if($this->createColumns('general_settings', 'cache_time', ['Type' => 'int default 600'])){

                        $this->add('general_settings', [
                            'fields' => ['cache_time' => 600],
                            'duplicate' => true
                        ]);

                    }

                }

            }

        }

    }

    public function checkCacheTable()
    {

        if (!in_array('cached_tables', $this->showTables())) {

            $query = "create table cached_tables
                    (
                        id int auto_increment,
                        name varchar(255) null,
                        date datetime null,
                        cache_time int null,
                        constraint cached_tables_pk
                            primary key (id)
                    )";

            if($this->query($query, 'c')){

                $queryIndex = "create unique index cached_tables_name_uindex
                            on cached_tables (name(190)) USING BTREE";

                return $this->query($queryIndex, 'c');

            }

            return false;

        }

        return true;

    }

    public function checkGeneralSettings(){

        if(!in_array('general_settings', $this->showTables())){

            $query = "create table general_settings
                    (
                        id int default 1 not null,
                        cache_time int null,
                        constraint general_settings_pk
                            primary key (id)
                    )";

            return $this->query($query, 'c');

        }

        return true;

    }

    public function getRealTables($tablesArr = []){

        !$tablesArr && $tablesArr = &$this->tableRows;

        $result = [];

        $dateTime = (new \DateTime())->format('Y-m-d H:i:s');


        foreach ($tablesArr as $table => $data)
            if(in_array($table, $this->showTables())) $result[$table] = $dateTime;


        return $result;

    }

    final public function getSettings(){

        $result = [];

        $res = $this->showTables();

        if($res){

            $settingsFields = [];

            $settingsTables = [];

            foreach ($res as $item){

                if(stripos($item, 'settings') !== false){

                    $fields = $this->showColumns($item);

                    $settingsTables[] = $item;

                    unset($fields['id_row'], $fields['multi_id_row']);

                    $fields_alias = false;

                    if($item !== 'settings'){

                        $item .= ' ' . $item;
                        $fields_alias = true;

                    }

                    $resFields = [];

                    foreach ($fields as $field => $values) $resFields[] = $field;

                    $settingsFields[] = trim($this->createFields(['fields' => $resFields, 'fields_alias' => $fields_alias], $item), ', ');

                }

            }

            if($settingsTables){

                $query = 'SELECT ' . (implode(',', $settingsFields)) . ' FROM ' . (implode(',', $settingsTables));

                $result = $this->query($query);

                if($result && count($result) === 1) $result = $result[0];

            }

        }

        return $result;

    }

    public function showForeignKeys($table = false, $key = false){

        $db = DB_NAME;

        $where = $key ? "AND COLUMN_NAME = '$key' LIMIT 1" : '';

        $tableRow = '';

        if($table){

            $table = "AND TABLE_NAME = '$table'";

        }else{

            $tableRow = ', TABLE_NAME ';

        }

        $query = "SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME $tableRow
                    FROM information_schema.KEY_COLUMN_USAGE
                      WHERE TABLE_SCHEMA = '$db' $table AND
                        CONSTRAINT_NAME <> 'PRIMERY' AND REFERENCED_TABLE_NAME is not null $where";

        return $this->query($query);

    }

}