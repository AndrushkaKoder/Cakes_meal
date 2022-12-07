<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 08.09.2019
 * Time: 15:38
 */

namespace core\admin\controller;

use core\exceptions\RouteException;
use settings\Settings;
use libraries\FileEdit;

class AjaxController extends BaseAdmin
{

    public function ajax(){

        $this->execBase();

        if($this->ajaxData){

            if(!empty($this->ajaxData['data']) && !is_array($this->ajaxData['data'])){

                $data = json_decode($this->ajaxData['data'], true);

                if($data && is_array($data)){

                    foreach ($data as $key => $item) $this->ajaxData[$key] = $item;

                }

            }

            if(!$this->columns && $this->ajaxData['table']){
                $this->columns = $this->model->showColumns($this->ajaxData['table']);
            }

        }

        if(!empty($this->ajaxData)){

            switch($this->ajaxData['ajax']){

                case 'search':
                    return $this->ajaxSearch();
                    break;


                case 'change_parent':

                    return $this->changeParent();
                    break;

                case 'sort_table':

                    return $this->sortTable();
                    break;

                case 'set_parent_id':

                    if(!empty($this->ajaxData['table']) && isset($this->ajaxData['id'])){

                        $_SESSION['checked_parents'][\AppH::clearStr($this->ajaxData['table'])] = \AppH::clearStr($this->ajaxData['id']);

                    }

                    break;

                case 'editData':

                    if(method_exists($this, 'checkDataCreators') && $this->checkDataCreators()){

                        throw new RouteException('Попытка доступа к запрещенному для пользователя '
                            . $this->userData['name'] . ' ресурсу edit таблицы ' . $this->table, 3);

                    }

                    $_POST['return_id'] = true;

                    $redirectPath = !empty($_POST['add_new_element']) ? $this->alias([\App::config()->WEB('alias') => 'add', $this->table]) : '';

                    $result = $this->checkPost();

                    !$redirectPath && $result &&
                    $redirectPath = $this->alias([\App::config()->WEB('alias') => 'add', $this->table => $result]);

                    return ['success' => $redirectPath];

                    break;

                case 'wyswyg_file':

                    if(method_exists($this, 'checkDataCreators')){

                        $blocking = false;

                        if(empty($this->ajaxData['tableId'])){

                            if(empty($this->userData['ROOT']) && empty($this->userData['credentials'][$this->table]['add'])){

                                $blocking = 'add';

                            }

                        }else{

                            $columns = $this->model->showColumns($this->table);

                            $_POST[$columns['id_row']] = $this->ajaxData['tableId'];

                            if($this->checkDataCreators()){

                                $blocking = 'edit';

                            }

                        }

                        if($blocking){

                            throw new RouteException('Попытка доступа к запрещенному для пользователя '
                                . $this->userData['name'] . ' ресурсу ' . $blocking . ' таблицы ' . $this->table, 3);

                        }

                    }

                    $dir = \AppH::clearStr($this->ajaxData['table'] . '/content_files');

                    $fileEdit = new FileEdit();

                    $fileEdit->setUnique(false);

                    $file = $fileEdit->addFile($dir);

                    return ['location' => \AppH::correctPath(\App::PATH(), \App::config()->WEB('upload_dir')) . $file[key($file)]];

                    break;

                case 'modify_file':

                    return $this->modifyFile();

                    break;

                case '1c_import':
                    return $this->import1C();
                    break;

                case 'after_1c_import':
                    return $this->afterImport1C();
                    break;

            }

        }

    }

    protected function modifyFile(){

        $validArr = ['id', 'id_row', 'row', 'table', 'fileName', 'data'];

        foreach ($validArr as $row){

            if(empty(trim($this->ajaxData[$row]))){

                return null;

            }

            if($row !== 'data'){

                $this->ajaxData[$row] = \AppH::clearStr($this->ajaxData[$row]);

            }

        }

        $res = $this->model->get($this->ajaxData['table'], [
            'fields' => [$this->ajaxData['row']],
            'where' => [$this->ajaxData['id_row'] => $this->ajaxData['id']],
            'single' => true
        ]);

        if(!$res || empty($res[$this->ajaxData['row']])){

            return null;

        }

        $fileName = substr($this->ajaxData['fileName'], strpos($this->ajaxData['fileName'], $this->ajaxData['table']));

        if($fileName){

            $data = json_decode($res[$this->ajaxData['row']], true);

            if(!$data){

                $data = [$res[$this->ajaxData['row']]];

            }

            if(in_array($fileName, $data)){

                $fileData = $this->ajaxData['data'];

                $fileData = preg_replace('/^data:image\/[^;]+;base64,/i', '', $fileData);

                $fileData = str_replace(' ', '+', $fileData);

                $fileData = base64_decode($fileData);

                $fullFileName = $_SERVER['DOCUMENT_ROOT'] . \AppH::correctPath(\App::PATH(), \App::config()->WEB('upload_dir')) . $fileName;

                if(file_exists($fullFileName)){

                    if(@file_put_contents($fullFileName, $fileData)){

                        (new FileEdit())->checkResizeFile($fullFileName);

                        return $this->ajaxData['fileName'] . '?v' . (str_replace(' ', '_', microtime()));

                    }

                }

            }

        }

        return null;

    }

    protected function afterImport1C(){

        $importController = new ImportController();

        if(method_exists($importController, 'afterImport')){

            try{

                return ['message' => $importController->afterImport()];

            }catch (\Exception $e){

                return ['success' => 0, 'message' => 'Error AfterImport logic'];

            }

        }

    }

    protected function import1C(){

        return (new ImportController())->inputData();

    }

    protected function ajaxSearch(){

        $data = \AppH::clearStr($this->ajaxData['data']);
        $table = \AppH::clearStr($this->ajaxData['table']);

        return $this->model->adminSearch($data, $table, 1, 20);

    }

    protected function changeParent(){

        $parentId = $this->ajaxData['parent_id'] ?: false;

        $res = $this->model->get($this->ajaxData['table'], [
            'fields' => ['COUNT(*) AS count'],
            'where' => ['parent_id' => $parentId],
            'single' => true
        ]);

        return (isset($res['count']) ? $res['count'] : 0) + (int)$this->ajaxData['iteration'];

    }

    protected function sortTable(){

        $idRow = $this->columns['id_row'];

        if($idRow && $this->ajaxData['table'] &&
            in_array($this->ajaxData['table'], $this->model->showTables())){

            if(!empty($this->userData['ROOT']) && !empty($this->columns[$this->ajaxData['current']])){

                if(array_search($idRow, array_keys($this->columns))){

                    $type = $this->columns[$idRow]['Type'];

                    $query = "ALTER TABLE {$this->ajaxData['table']} MODIFY COLUMN $idRow $type FIRST";

                    $this->model->query($query, 'u');

                }

                $after = !empty($this->ajaxData['previous']) && $this->ajaxData['previous'] !== 'null' ? $this->ajaxData['previous'] : $idRow;

                $type = $this->columns[$this->ajaxData['current']]['Type'];

                $query = "ALTER TABLE {$this->ajaxData['table']} MODIFY COLUMN {$this->ajaxData['current']} $type AFTER $after";

                $this->model->query($query, 'u');

            }

            $columns = false;

            if(Settings::get('tableParameters') && method_exists($this->model, 'setTableParameters')){

                if(in_array(Settings::get('tableParameters'), $this->model->showTables())){

                    $res = $this->model->get(Settings::get('tableParameters'), [
                        'fields' => ['sorting'],
                        'where' => ['users_id' => $this->userData['id'], 'table_name' => $this->ajaxData['table']],
                        'single' => true
                    ]);

                    if(!empty($res['sorting'])){

                        $columns = json_decode($res['sorting'], true);

                    }

                }


                if(!$columns){

                    $columns = $this->model->showColumns($this->ajaxData['table']);

                    if(!empty($columns)){

                        unset($columns['id_row'], $columns['multi_id_row']);

                        $columns = array_keys($columns);

                    }

                }

                if(!empty($columns)){

                    $currentKey = array_search($this->ajaxData['current'], $columns);

                    if($currentKey !== false){

                        unset($columns[$currentKey]);

                        $columns = array_values($columns);

                    }

                    $after = !empty($this->ajaxData['previous']) && $this->ajaxData['previous'] !== 'null' ?
                        array_search($this->ajaxData['previous'], $columns) : 0;

                    if($after === false){

                        $after = count($columns) - 1;

                    }

                    array_splice($columns, $after + 1, 0, $this->ajaxData['current']);

                    return $this->model->setTableParameters($this->ajaxData['table'], ['sorting' => $columns]);


                }

            }

        }

        return null;
    }

}