<?php

namespace libraries\Import1C;

use core\admin\controller\BaseAdmin;
use core\admin\model\Model;
use libraries\FileEdit;
use libraries\TextModify;

class Import1C extends BaseAdmin
{

    protected $directory;

    protected $goodsTable = 'goods';
    protected $offersTable = 'offers';
    protected $catalogTable = 'catalog';
    protected $filtersTable = 'filters';
    protected $filtersGoodsTable = 'filters_goods';

    protected $goodsOffersPricesChanged = [];

    protected $processedElements = [];

    /*Очистка данных*/
    public $clearDirectory = true;
    public $hideEmptyCatalog = true;
    public $clearImportTablesBeforeImport = true;
    /*Очистка данных*/


    protected function inputData()
    {
        set_time_limit(0);

        ini_set('mysql.connect_timeout','200');

        $this->model = Model::instance();

        if(!empty($this->clearImportTablesBeforeImport) && method_exists($this, 'clearImportTables')){

            $this->clearImportTables();

        }

        $dir = $this->setDirectory();

        $filesArr = ['import', 'offers', 'prices', 'rests'];

        $fileExtension = 'xml';

        foreach ($filesArr as $file){

            $fileExists = $this->searchFile($dir, $file, $currentDir);

            if($fileExists){

                foreach ($fileExists as $importFile){

                    $xml = simplexml_load_file($importFile);

                    $data = json_decode(json_encode($xml), true);

                    if($data){

                        switch ($file){

                            case 'import':

                                $this->createImport($data, $importFile);

                                break;

                            case 'offers':

                                $this->createOffers($data);

                                break;

                            case 'prices':

                                $this->createPrices($data);

                                break;

                            case 'rests':

                                $this->createRests($data);

                                break;

                        }

                    }

                }

            }

        }

        $this->setMinimalPriceAndRestsForGoods();

        if($this->clearDirectory){

            $this->clearDir($dir);

        }

        if($this->hideEmptyCatalog){

            $this->hideEmptyCatalog();

        }

        return $this->processedElements;

    }

    protected function hideEmptyCatalog(){

        if(!empty($this->model->showColumns($this->goodsTable)['parent_id'])
            && !empty($this->model->showColumns($this->goodsTable)['visible'])
            && !empty($this->model->showColumns($this->catalogTable)['visible'])){

            $goods = $this->model->get($this->goodsTable, [
                'fields' => ['parent_id'],
                'where' => ['visible' => 1],
                'distinct' => true
            ]);

            if($goods){

                $catalogIds = $this->getParents(array_column($goods, 'parent_id'), $this->catalogTable);

                if($catalogIds){

                    $this->model->edit($this->catalogTable, [
                        'fields' => ['visible' => 0],
                        'where' => ['!id' => $catalogIds]
                    ]);

                    $this->model->edit($this->catalogTable, [
                        'fields' => ['visible' => 1],
                        'where' => ['id' => $catalogIds]
                    ]);

                }

            }

        }

    }

    protected function clearImportTables(){

        $dir = $_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR;

        foreach ($this as $propName => $table){

            if(!preg_match('/table$/i', $propName)){

                continue;

            }

            if(!empty($table) && in_array($table, $this->model->showTables()) && !empty($this->model->showColumns($table)['id_row'])){

                $res = $this->model->delete($table, [
                    'where' => ['>' . $this->model->showColumns($table)['id_row'] => 0]
                ]);

                if($res && ($propName === 'goodsTable' || $propName === 'offersTable')){

                    $this->clearDir($dir . $table);

                }

            }

        }

    }

    protected function setMinimalPriceAndRestsForGoods(){

        if($this->goodsOffersPricesChanged){

            foreach ($this->goodsOffersPricesChanged as $id => $value){

                $offer = $this->model->get($this->offersTable, [
                    'fields' => ['price'],
                    'where' => ['parent_id' => $id, '!price' => false],
                    'order' => 'price',
                    'limit' => 1,
                    'single' => true
                ]);

                if($offer){

                    $fields = [
                        'price' => $offer['price']
                    ];

                    if($value !== true){

                        $fields['quantity'] = $value;

                    }

                    $this->model->edit($this->goodsTable, [
                        'fields' => $fields,
                        'where' => ['id' => $id]
                    ]);

                }

            }

        }

    }

    protected function createRests($data){

        if(!empty($data['ПакетПредложений']['Предложения']['Предложение'])){

            $data = (array)$data['ПакетПредложений']['Предложения']['Предложение'];

            if(!is_numeric(key($data)))
                $data = [$data];

            foreach ($data as $item){

                if(!empty($item['Остатки']['Остаток'])){

                    $rests = $item['Остатки']['Остаток'];

                    $quantity = 0;

                    if(!is_numeric(key($rests)))
                        $rests = [$rests];

                    foreach ($rests as $store){

                        if(!empty($store['Склад']['Количество'])){

                            $quantity += $this->clearNum($store['Склад']['Количество']);

                        }

                        if (!empty($store['Количество'])){

                            $quantity += $this->clearNum($store['Количество']);

                        }

                    }

                    $table = preg_match('/#/', $item['Ид']) ? $this->offersTable : $this->goodsTable;

                    $element = $this->model->get($table, [
                        'where' => ['1c_id' => $item['Ид']],
                        'limit' => 1,
                        'single' => true
                    ]);

                    if($element){

                        $this->model->edit($table, [
                            'fields' => ['quantity' => $quantity],
                            'where' => ['id' => $element['id']]
                        ]);

                        if($table === $this->offersTable){

                            $this->goodsOffersPricesChanged[$element['parent_id']] = $quantity;

                        }

                    }

                }

            }

        }

    }

    protected function createPrices($data){

        $priceTypes = [0];

        if(!empty($data['ПакетПредложений']['Предложения']['Предложение'])){

            $data = (array)$data['ПакетПредложений']['Предложения']['Предложение'];

            foreach ($data as $item){

                if(!empty($item['Цены']['Цена'])){

                    $table = preg_match('/#/', $item['Ид']) ? $this->offersTable : $this->goodsTable;

                    $prices = (array)$item['Цены']['Цена'];

                    if(!is_numeric(key($prices)))
                        $prices = [$prices];

                    foreach ((array)$priceTypes as $type){

                        if(!empty($prices[$type]) && !empty($prices[$type]['ЦенаЗаЕдиницу'])){

                            $price = $this->clearNum($prices[$type]['ЦенаЗаЕдиницу']);

                            $elementForSetPrice = $this->model->get($table, [
                                'where' => ['1c_id' => $item['Ид']],
                                'limit' => 1,
                                'single' => true
                            ]);

                            if($elementForSetPrice){

                                $this->model->edit($table, [
                                    'fields' => ['price' => $price],
                                    'where' => ['id' => $elementForSetPrice['id']]
                                ]);

                                if($table === $this->offersTable){

                                    if(!array_key_exists($elementForSetPrice['parent_id'], $this->goodsOffersPricesChanged)){

                                        $this->goodsOffersPricesChanged[$elementForSetPrice['parent_id']] = true;

                                    }

                                }

                            }

                        }

                    }


                }

            }

        }

    }

    protected function createOffers($data){

        if(!empty($data['ПакетПредложений']['Предложения']['Предложение'])){

            $data = (array)$data['ПакетПредложений']['Предложения']['Предложение'];

            if(!is_numeric(key($data)))
                $data = [$data];

            $table = $this->offersTable;

            $goodsTable = $this->goodsTable;

            $addFields = [
                'name' => 'Наименование',
                '1c_id' => 'Ид',
            ];

            foreach ($data as $item){

                $fields = [];

                foreach ($addFields as $key => $value){

                    if(isset($item[$value])){
                        $fields[$key] = $item[$value];
                    }

                }

                if($fields && !empty($fields['1c_id'])){

                    $idArr = preg_split('/#+/', $fields['1c_id'], 2, PREG_SPLIT_NO_EMPTY);

                    if(count($idArr) > 1){

                        $goods1CId = $idArr[0];

                        $goodsRes = $this->model->get($goodsTable, [
                            'where' => ['1c_id' => $goods1CId],
                            'limit' => 1,
                            'single' => true
                        ]);

                        if(!$goodsRes){

                            continue;

                        }

                        $fields['parent_id'] = $goodsRes['id'];

                        $this->setOffer($table, $fields);

                    }

                }

            }

        }

    }

    protected function setOffer($table, $data){

        if(!$table || !$data || empty($data['1c_id']) || empty($data['parent_id'])){

            return null;

        }

        $res = $this->model->get($table, [
            'where' => ['1c_id' => $data['1c_id'], 'parent_id' => $data['parent_id']],
            'limit' => 1,
            'single' => true
        ]);

        if($res){

            if(is_numeric($res['parent_id'])){

                $res['parent_id'] = $this->clearNum($res['parent_id']);

            }

            if(is_numeric($data['parent_id'])){

                $data['parent_id'] = $this->clearNum($data['parent_id']);

            }

            if($data['parent_id'] !== $res['parent_id']){

                $this->model->edit($table, [
                    'fields' => ['parent_id' => $data['parent_id']],
                    'where' => ['id' => $res['id']]
                ]);

            }

        }else{

            $this->model->add($table, [
                'fields' => $data,
                'duplicate' => true
            ]);

        }

    }

    protected function createImport($data, $fileName = ''){

        if(!$data) return;

        static $units = [];

        if(!empty($data['Классификатор']['Группы']['Группа'])){

            $this->createCatalog($data['Классификатор']['Группы']['Группа']);

            if(!empty($data['Классификатор']['ЕдиницыИзмерения']['ЕдиницаИзмерения'])){

                foreach ($data['Классификатор']['ЕдиницыИзмерения']['ЕдиницаИзмерения'] as $item){

                    if(!is_array($item['Код'])){

                        $code = trim($item['Код']);

                        if($code){

                            $units[$code] = trim($item['НаименованиеКраткое']);

                        }

                    }

                }

            }

        }

        if (!empty($data['Каталог']['Товары']['Товар'])){

            $this->createGoods($data['Каталог']['Товары']['Товар'], $units, $fileName);

        }

    }

    protected function createGoods($data, $units = [], $fileName = ''){

        $addFields = [
            'name' => 'Наименование',
            '1c_id' => 'Ид',
            'article' => 'Артикул'
        ];

        $table = $this->goodsTable;

        $catalogTable = $this->catalogTable;

        if(!is_numeric(key($data)))
            $data = [$data];

        foreach ($data as $item){

            $parentId = null;

            $visible = 1;

            if(!empty($item['Группы']['Ид'])){

                $res = $this->model->get($catalogTable, [
                    'fields' => ['id', 'visible'],
                    'where' => ['1c_id' => $item['Группы']['Ид']],
                    'single' => true
                ]);

                if($res){

                    $parentId = $res['id'];

                    $visible = $res['visible'];

                }

            }

            $fields = [];

            foreach ($addFields as $key => $value){

                if(isset($item[$value])){
                    $fields[$key] = $item[$value];
                }

            }

            $fields['parent_id'] = $parentId;

            $fields['visible'] = $visible;

            if(!empty($item['БазоваяЕдиница']) && !is_array($item['БазоваяЕдиница'])){

                $code = trim($item['БазоваяЕдиница']);

                if(!empty($units[$code])){

                    $fields['unit'] = $units[$code];

                }

            }

            if(!empty($item['Картинка'])){

                $img = null;

                $fileInfo = pathinfo($fileName);

                if(!empty($fileInfo['dirname'])){

                    $images = $this->creacteImg((array)$item['Картинка'], $fields['name'], $table, $fileInfo['dirname']);

                    if($images){

                        foreach ($images as $img){

                            if(empty($fields['img'])){

                                $fields['img'] = $img;

                            }else{

                                $fields['gallery_img'][] = $img;

                            }

                        }

                    }

                }

            }

            $id = $this->addElement($item, $parentId, $fields, $table);

            if($id){

                $this->addFilters($id, $item);

            }

        }

    }

    protected function addFilters($id, $data){

        $data = !empty($data['ЗначенияРеквизитов']['ЗначениеРеквизита']) ? (array)$data['ЗначенияРеквизитов']['ЗначениеРеквизита'] : null;

        if($data && !is_numeric(key($data)))
            $data = [$data];

        $table = $this->filtersTable;

        $mTable = $this->filtersGoodsTable;

        $goodsRow = 'goods_id';

        $filtersRow = 'filters_id';

        $visibleRow = 'ПродаетсяНаСайте';

        $excludedProperties = [
            'ВидНоменклатуры',
            'ТипНоменклатуры',
            'Полное наименование',
            'Код',
            'Планируемая дата поступления',
            'ПродаетсяНаСайте',
            'ОписаниеФайла'
        ];

        $propertyRows = [
            'Наименование' => 'name',
            'Значение' => 'value'
        ];

        if(!$id || !$data){

            return null;

        }

        $setProperties = [];

        $setVisible = null;

        foreach ($data as $key => $item){

            $propArr = [];

            foreach ($item as $k => $v){

                if(!empty($v) && !empty($propertyRows[$k])){

                    $propArr[$propertyRows[$k]] = $v;

                }

            }

            if(count($propArr) !== count($propertyRows) || in_array($propArr['name'], $excludedProperties)){

                if($visibleRow === $propArr['name']){

                    $setVisible = (int)($propArr['value'] === 'true');

                }

                continue;

            }

            $parentId = $this->checkExistsProperty($propArr['name'], $table);

            if($parentId){

                $propId = $this->checkExistsProperty($propArr['value'], $table, $parentId);

                if($propId){

                    $setProperties[$key][$goodsRow] = $id;

                    $setProperties[$key][$filtersRow] = $propId;

                }

            }

        }

        if($setProperties){

            $this->model->delete($mTable, [
                'where' => [$goodsRow => $id]
            ]);

            $this->model->add($mTable, [
                'fields' => $setProperties
            ]);

        }

        if(isset($setVisible) && !empty($this->model->showColumns($this->goodsTable)['visible'])){

            $this->model->edit($this->goodsTable, [
                'fields' => ['visible' => $setVisible],
                'where' => ['id' => $id]
            ]);

        }

    }

    protected function checkExistsProperty($property, $table, $parentId = null){

        if(!$property || !$table){

            return null;

        }

        $property = $this->clearStr($property, false);

        $res = $this->model->get($table, [
            'where' => ['name{lower}' => mb_strtolower($property), 'parent_id' => $parentId],
            'limit' => 1,
            'single' => true
        ]);

        if($res){

            return $res['id'];

        }

        $pos = ++$this->model->get($table, [
            'fields' => ['COUNT(*) as count'],
            'where' => ['parent_id' => $parentId],
            'single' => true
        ])['count'];

        $id = $this->model->add($table, [
            'fields' => ['name' => $property, 'parent_id' => $parentId, 'menu_position' => $pos],
            'return_id' => true
        ]);

        if(!$id || !is_numeric($id)){

            return null;

        }

        return $id;

    }

    protected function createCatalog($data, $parentId = null){

        $addFields = [
            'name' => 'Наименование',
            '1c_id' => 'Ид'
        ];

        $table = $this->catalogTable;

        if(!is_numeric(key($data)))
            $data = [$data];

        foreach ($data as $item){

            $id = null;

            $fields = [];

            foreach ($addFields as $key => $value){

                if(isset($item[$value])){
                    $fields[$key] = $item[$value];
                }

            }

            $fields['parent_id'] = $parentId;

            $id = $this->addElement($item, $parentId, $fields, $table);

            if(!empty($item['Группы']['Группа'])){

                $this->createCatalog($item['Группы']['Группа'], $id);

            }

        }

    }

    protected function addElement($item, $parentId, $fields, $table){

        $this->table = $table;

        $el = $this->model->get($table, [
            'where' => ['1c_id' => $item['Ид']],
            'single' => true
        ]);

        if($el){

            $id = $el['id'];

            $this->model->edit($table, [
                'fields' => $fields,
                'where' => ['id' => $el['id']]
            ]);

            $dir = $_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $table . '/';

            if(!empty($fields['img']) && !empty($el['img'])){

                @unlink($dir . $el['img']);

            }

            if(!empty($fields['gallery_img']) && !empty($el['gallery_img'])){

                foreach (json_decode($el['gallery_img'], true) as $img)
                    @unlink($dir . $img);

            }

        }else{

            if(!empty($this->model->showColumns($table)['menu_position']) && empty($fields['menu_position'])){

                $fields['menu_position'] = ++$this->model->get($table, [
                    'fields' => ['COUNT(*) as count'],
                    'where' => ['parent_id' => $parentId],
                    'single' => true
                ])['count'];

            }

            if(!empty($this->model->showColumns($table)['visible']) && !isset($fields['visible'])){

                $fields['visible'] = 1;

            }

            $id = $this->model->add($table, [
                'fields' => $fields,
                'return_id' => true
            ]);

            if(!$id)
                exit('Ошибка добавления элемента');

            $fields['id'] = $id;

            $fields = $this->createAlias($id, $fields);

            $alias = $fields['alias'] ?? ($this->alias ?? $id);

            $res = $this->model->get($table, [
                'where' => ['alias' => $alias, '!id' => $id],
                'limit' => 1
            ]);

            if($res)
                $alias .= '-' . $id;

            $this->model->edit($table, [
                'fields' => ['alias' => $alias],
                'where' => ['id' => $id]
            ]);

        }

        if($id){

            $row = 'Товары';

            if ($table === $this->catalogTable){

                $row = 'Категории';

            }

            if(!isset($this->processedElements[$row])){

                $this->processedElements[$row] = 0;

            }

            $this->processedElements[$row]++;

        }

        return $id;

    }

    protected function creacteImg($files, $newFileName, $table, $directory){

        $dir = $_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $table;

        $directory .= '/';

        $fileEdit = new FileEdit();

        $fileEdit->setDirectory($table);

        $res = [];

        foreach ($files as $key => $file){

            $name = preg_replace('/\/{2,}/', '/', $directory . $file);

            if($file && file_exists($name)){

                $fileArr = pathinfo($name);

                $ext = $fileArr['extension'];

                $fileName = TextModify::getTranslit($newFileName);

                $fileName = $fileEdit->checkFile($fileName, $ext);

                if (copy($name, $dir . '/' . $fileName)) {

                    $res[$key] = $table . '/' . $fileName;

                }

            }

        }

        return $res;

    }

    protected function searchFile($dir, $fileName, &$currentDir){

        $searchRes = [];

        if(!preg_match('/\/\s*$/', $dir)) $dir .= '/';

        $list = scandir($dir);

        $directories = [];

        if($list){

            foreach ($list as $file)
            {

                if($file !== '.' && $file !== '..'){

                    if (is_dir($dir.$file))
                    {
                        $directories[] = $dir . $file . '/';
                    }
                    else
                    {

                        if(mb_stripos($file, $fileName) !== false){

                            $currentDir = $dir;

                            $searchRes[] = $dir . $file;

                        }

                    }

                }


            }

        }

        if($directories){

            foreach ($directories as $item){

                if(($res = $this->searchFile($item, $fileName, $currentDir))){
                    $searchRes = array_merge($searchRes, $res);
                }

            }

        }

        return $searchRes;

    }

    protected function clearDir($dir)
    {

        if(!preg_match('/\/\s*$/', $dir)) $dir .= '/';

        $list = scandir($dir);

        if($list){

            unset($list[0],$list[1]);

            foreach ($list as $file)
            {
                if (is_dir($dir.$file))
                {
                    $this->clearDir($dir . $file .'/');

                    @rmdir($dir . $file);
                }
                else
                {
                    @unlink($dir . $file);
                }
            }

        }

    }

    public function setDirectory($dir = ''){

        if(!$this->directory){

            if($dir){

                if(stripos($dir, $_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR) === false){

                    $dir = $_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $dir . '/';

                }

                $dir = preg_replace('/\/{2,}/', '/', $dir);

            }else{

                $dir = $_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . '1c_import/';

            }

            $this->directory = $dir;

            if(!is_dir($dir)){

                if(!mkdir($dir, 0777, true)){

                    $this->directory = $dir = false;

                }

            }

        }

        return $this->directory;

    }

}