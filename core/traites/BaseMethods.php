<?php

namespace core\traites;

use core\models\UserModel;

trait BaseMethods
{

    protected function getChildren($category, $table, $idRow = null, $checkVisible = false){

        $columns = $this->model->showColumns($table);

        !$idRow && $idRow = $columns['id_row'];

        $id = is_array($category) ? $category[$columns['id_row']] : $category;

        if(empty($columns['parent_id']))
            return $id;

        static $categoriesDb = [];

        if(empty($categoriesDb[$table])){

            $categoriesDb[$table] = $this->model->get($table, [
                'where' => $checkVisible && !empty($columns['visible']) ? ['visible' => 1] : [],
                'order' => 'parent_id',
                'order_direction' => 'DESC'
            ]);

        }

        $categories = $this->recursiveArr($categoriesDb[$table], 1, $id, $idRow);

        $ids = [];

        $ids[] = $id;

        if($categories){

            foreach($categories as $item){

                if(!array_key_exists('old_parent_id', $item) && $item['parent_id'] === $id){

                    $ids[] = $item[$columns['id_row']];

                    if(!empty($item['sub'])){

                        foreach ($item['sub'] as $subId => $value){

                            $ids[] = $subId;

                        }

                    }

                }

            }

        }

        return $ids;

    }

    protected function getParents($ids, $table, $parentRow = 'parent_id'){

        if(!$ids) return [];

        if(!is_array($ids)) $ids = (array)$ids;

        $model = !empty($this->model) ? $this->model : $this;

        $columns = $model->showColumns($table);

        if(empty($columns[$parentRow]))
            return $ids;

        $whereIds = $ids;

        while ($whereIds){

            $data = $model->get($table, [
                'fields' => [$parentRow],
                'where' => [$columns['id_row'] => $whereIds],
                'no_check_credentials' => true,
                'group' => $parentRow,
            ]);

            if(!$data){

                $whereIds = null;

                continue;

            }

            $whereIds = array_column($data, $parentRow);

            $ids = array_merge($ids, $whereIds);

            if(($keys = array_keys($ids, null))){

                foreach ($keys as $key => $item){

                    unset($ids[$item]);

                }

            }

        }

        return array_unique($ids);

    }

    protected function writeLog($message, $file = 'log.txt', $event = 'Fault', $rotateLogs = true){

        $dateTime = new \DateTime();

        if($event !== 0) $str = $event . ': ' . $dateTime->format('d-m-Y G:i:s') . ' - ' . $message . "\r\n";
        else $str = $message . "\r\n";

        $dir = $_SERVER['DOCUMENT_ROOT'] . \App::PATH() . 'log';

        if(!is_dir($dir)){

            mkdir($dir, 0777);

        }

        $fileArr = preg_split('/\./', $file, 0, PREG_SPLIT_NO_EMPTY);

        if(!empty($fileArr[count($fileArr) - 2])){

            $fileArr[count($fileArr) - 2] .= '_' . $dateTime->format('Y_m_d');

            $file = implode('.', $fileArr);

        }

        if($rotateLogs){

            $this->rotateLogs($dir);

        }

        file_put_contents($dir . '/' . $file, $str, FILE_APPEND);

    }

    protected function rotateLogs($dir, $day = 30){

        $list = scandir($dir);

        if($list){

            foreach ($list as $file){

                if($file !== '.' && $file !== '..' && !is_dir($dir . '/' . $file) && !is_link($dir . '/' . $file)){

                    if((new \DateTime(date('Y-m-d', filemtime($dir . '/' . $file)))) < (new \DateTime())->modify('-' . $day . ' day')){

                        @unlink($dir . '/' . $file);

                    }

                }

            }

        }

    }

    protected function addSessionData(){
        if(\AppH::isPost()){
            foreach ($_POST as $key => $value){
                $_SESSION['res'][$key] = $value;
            }
            \AppH::redirect();
        }
    }

    protected function setFormValues($key, $property = null, $arr = []){

        if(!$arr){

            $arr = $_SESSION['res'] ?? [];

        }

        if(!empty($arr[$key])){

            return $arr[$key];

        }elseif ($property && !empty($this->$property[$key])){

            return $this->$property[$key];

        }

        return '';

    }

    protected function checkAuth($type = false){

        if(!($this->userData = UserModel::instance()->checkUser(false, $type))){

            $type && \AppH::redirect(\App::PATH());

        }

        if(property_exists($this, 'userModel'))
            $this->userModel = UserModel::instance();

        if(property_exists($this, 'model') && $this->model)
            $this->model->userData = $this->userData;


    }
}