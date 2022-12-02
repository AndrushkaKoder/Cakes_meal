<?php

namespace core\admin\controller;

use libraries\Import1C\Import1C;

class ImportController extends BaseAdmin
{

    public $redirect = true;

    protected function inputData()
    {

        $this->checkApiImport();

        $this->checkManualImport();

        if($this->redirect){

            $this->redirect();

        }

        return $_SESSION['res']['answer'] ?? null;

    }

    protected function checkManualImport(){

        parent::inputData();

        if($this->isPost()){

            $import1C = new Import1C();

            $dir = $import1C->setDirectory();

            if(!empty($_FILES['import']['name'])){

                if($_FILES['import']['type'] === 'application/x-zip-compressed' && $dir){

                    $zip = new \ZipArchive;

                    if ($zip->open($_FILES['import']['tmp_name']) === TRUE) {

                        $zip->extractTo($dir);
                        $zip->close();

                    }else{

                        $_SESSION['res']['answer'] = '<div class="error">Ошибка распаковки архива</div>';

                        $this->redirect();

                    }

                }elseif ($_FILES['import']['type'] !== 'text/xml'){

                    $_SESSION['res']['answer'] = '<div class="error">Некорректный формат файла выгрузки</div>';

                    $this->redirect();

                }

            }

            $res = $import1C->inputData();

            if(empty($res)){

                $_SESSION['res']['answer'] = '<div class="error">Нет обработанных элементов</div>';

            }else{

                $str = 'Обработано<br>';

                foreach ($res as $key => $item){

                    $str .= $key . ' - ' . $item . '<br>';

                }

                $_SESSION['res']['answer'] = '<div class="success">' . $str . '</div>';

            }

        }

    }

    protected function checkApiImport(){

        if(!empty($_GET['type']) && !empty($_GET['mode'])){

            if($_GET['type'] === 'catalog'){

                if($_GET['mode'] === 'checkauth'){

                    $res = (new LoginController())->APIAuth();

                    $this->responseApiAuth($res);

                }else{

                    if(empty($_COOKIE['PHPSESSID']) || $_COOKIE['PHPSESSID'] !== session_id()){

                        exit('Куку охибка!!!');

                    }

                    switch ($_GET['mode']){

                        case 'init':

                            $this->initExchange();

                            break;

                        case 'file':

                            $this->saveExchangeFiles();

                            break;

                        case 'import':

                            $this->importFile();

                            break;

                    }

                }

            }

        }

    }

    protected function importFile(){

        $response = [];

        $import1C = new Import1C();

        $dir = $import1C->setDirectory();

        if(empty($_SESSION['import'])){

            $res = $import1C->inputData();

            $response[] = 'success';

            if(empty($res)){

                $response[] = 'Нет обработанных элементов';

            }else{

                $response[] = 'Обработано:';

                foreach ($res as $key => $item){

                    $response[] = $key . ' - ' . $item;

                }

            }

        }else{

            if(file_exists($_SESSION['import'])){

                if(stripos($_SESSION['import'], $dir) === false){

                    $response[] = 'failure';

                    $response[] = 'Несоответствие директорий запрошенного файлы и директории выгрузки';

                }else{

                    $zip = new \ZipArchive;

                    if ($zip->open($_SESSION['import']) === TRUE) {

                        $zip->extractTo($dir);
                        $zip->close();

                        $response[] = 'progress';

                        $response[] = 'Файл успешно разархивирован';

                        @unlink($_SESSION['import']);

                    }else{

                        $response[] = 'failure';

                        $response[] = 'Ошибка при попытке извлечения архива ' . $_SESSION['import'];

                    }

                    unset($_SESSION['import']);

                }

            }

        }

        exit(implode("\n", $response));

    }

    protected function saveExchangeFiles(){

        $response = [];

        $dir = (new Import1C())->setDirectory();

        $data = file_get_contents("php://input");

        if(!$data){

            $response[] = 'failure';

            $response[] = 'Отсутствуют данные в потоке ввода';

        }else{

            if (!($fp = fopen($dir . $_GET['filename'], "ab"))){

                $response[] = 'failure';

                $response[] = 'Невозможно записать файл на диск';

            }else{

                fwrite($fp, $data);

                $response[] = 'success';

                $_SESSION['import'] = $dir . $_GET['filename'];

            }

        }

        exit(implode("\n", $response));

    }

    protected function initExchange(){

        $postSize = ini_get('post_max_size');

        $fileSize = ini_get('upload_max_filesize');

        $size = $fileSize < $postSize ? $fileSize : $postSize;

        if(preg_match('/\s*\D+\s*$/', $size, $matches)){

            $coefficient = 1;

            $char = substr(trim(strtolower($matches[0])), 0, 1);

            switch ($char){

                case 'k':

                    $coefficient = 1024;

                    break;

                case 'm':

                    $coefficient = 1024 * 1024;

                    break;

                case 'g':

                    $coefficient = 1024 * 1024 * 1024;

                    break;

            }

            $size *= $coefficient;

        }

        $response[] = 'zip=yes';

        $response[] = 'file_limit=' . $size;

        exit(implode("\n", $response));

    }

    protected function responseApiAuth($result){

        $response = [];

        if($result){

            $response[] = 'success';

            $response[] = 'PHPSESSID';

            $response[] = $_SESSION['PHPSESSID'] = $_COOKIE['PHPSESSID'] = session_id();

            $response[] = 'sessid=' . $_COOKIE['PHPSESSID'];

            $response[] = 'timestamp=' . $_SERVER['REQUEST_TIME'];

        }else{

            $response[] = 'failure';

            if(!empty($_SESSION['res']['answer'])){

                $response[] = $_SESSION['res']['answer'];

                unset($_SESSION['res']);

            }

        }

        exit(implode("\n", $response));

    }

}