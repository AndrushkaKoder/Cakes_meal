<?php

namespace core\admin\helpers;

use libraries\FileEdit;

trait PrepareFilesHelper
{

    protected function createFiles($id = false){

        $fileEdit = new FileEdit();

        $this->fileArray = $fileEdit->addFile($this->table . ($id ? '/' . $id : ''));

        if($id){

            $this->checkFiles($id);

        }elseif($this->fileArray && !empty($_POST)){

            foreach ($this->fileArray as $key => $item){

                if(array_key_exists($key, $_POST)){

                    $exists = false;

                    if(is_array($item)){

                        foreach ($item as $k => $v){

                            if($v && array_key_exists($k, $_POST[$key]) && array_key_exists('file', $_POST[$key][$k])){

                                $_POST[$key][$k]['file'] = $v;

                                $exists = true;

                            }

                        }

                    }

                    if(!$exists && is_array($item)){

                        foreach ($item as $k => $v){

                            @unlink(\AppH::correctPath(\App::FULL_PATH(), \App::config()->WEB('upload_dir')) . $v);

                        }

                    }

                    unset($this->fileArray[$key]);

                }

            }


        }

        if(!empty($_POST['js-sorting']) && $this->fileArray){

            foreach ($_POST['js-sorting'] as $key => $item){

                if(!empty($item) && !empty($this->fileArray[$key])){

                    $fileArr = json_decode($item);

                    if($fileArr){

                        $this->fileArray[$key] = $this->sortingFiles($fileArr, $this->fileArray[$key]);

                    }

                }

            }

        }



        if(!empty($_SESSION['crop_image'])){
            foreach ($_SESSION['crop_image'] as $key => $item) {
                $item['img'] = \AppH::correctPath(\App::FULL_PATH(), \App::config()->WEB('upload_dir')) . $this->fileArray[$key];
                $this->fileArray['thumbnails'][] = $fileEdit->createJsThumbnail($item, $item['thumb_name']);
            }
        }
    }

    protected function checkFiles($id){

        if($id){

            $arrKeys = [];

            if(!empty($this->fileArray)) $arrKeys = array_keys($this->fileArray);

            if(!empty($_POST['js-sorting'])) $arrKeys = array_merge($arrKeys, array_keys($_POST['js-sorting']));

            if($arrKeys){

                $arrKeys = array_unique($arrKeys);

                $data = $this->model->get($this->table, [
                    'fields' => $arrKeys,
                    'where' => [$this->columns['id_row'] => $id]
                ]);

                if($data){

                    $updateData = [];

                    $data = $data[0];

                    foreach ($data as $key => $item){

                        if((!empty($this->fileArray[$key]) && is_array($this->fileArray[$key])) || !empty($_POST['js-sorting'][$key])){

                            $fileArr = json_decode($item, true);

                            if($fileArr){

                                foreach ($fileArr as $k => $file){

                                    if(!is_array($file) && !is_object($file)){

                                        $this->fileArray[$key][] = $file;

                                    }elseif (isset($fileArr[$k]['file']) && !empty($this->fileArray[$key][$k])){

                                        $fileArr[$k]['file'] = $this->fileArray[$key][$k];

                                        $this->fileArray[$key] = $fileArr;

                                    }

                                }

                            }

                        }elseif(!empty($this->fileArray[$key])){

                            @unlink(\AppH::correctPath(\App::FULL_PATH(), \App::config()->WEB('upload_dir')) . $item);

                        }

                    }

                }

            }

        }

    }

    protected function sortingFiles($fileArr, $arr){

        $res = [];

        foreach ($fileArr as $file){

            if(!is_numeric($file)){

                $file = substr($file, strlen(\AppH::correctPath(\App::PATH(), \App::config()->WEB('upload_dir'))));

            }else{

                $file = $arr[$file];

            }

            if($file && in_array($file, $arr)){

                $res[] = $file;

            }

        }

        return $res;

    }

    protected function checkJsModifiedFiles(){

        if(!empty($_POST['js_modified_files']) && is_array($_POST['js_modified_files'])){

            $data = $this->model->get($this->table, [
                'where' => [$this->columns['id_row'] => $_POST[$this->columns['id_row']]],
                'single' => true
            ]);

            if($data){

                $fileEdit = new FileEdit();

                foreach ($_POST['js_modified_files'] as $rowName => $fileData){

                    if(!empty($data[$rowName])){

                        if(is_array($fileData)){

                            $rowData = json_decode($data[$rowName], true);

                            if($rowData){

                                foreach ($fileData as $key => $item){

                                    $fullFileName = '';

                                    if(!empty($_POST['js-sorting'][$rowName])){

                                        $sortingData = json_decode($_POST['js-sorting'][$rowName], true);

                                        if($sortingData){

                                            $rowKey = array_search($key, $sortingData);

                                            if($rowKey !== false && !empty($rowData[$rowKey]) && is_string($rowData[$rowKey]))

                                            $fullFileName = \AppH::correctPath(\App::FULL_PATH(), \App::config()->WEB('upload_dir')) . $rowData[$rowKey];

                                        }

                                    }

                                    if(!$fullFileName && !empty($rowData[$key])){

                                        if(is_array($rowData[$key]) && !empty($rowData[$key]['file'])){

                                            $fullFileName = \AppH::correctPath(\App::FULL_PATH(), \App::config()->WEB('upload_dir')) . $rowData[$key]['file'];

                                        }elseif(!is_string($rowData[$key])){

                                            $fullFileName = \AppH::correctPath(\App::FULL_PATH(), \App::config()->WEB('upload_dir')) . $rowData[$key];

                                        }

                                    }

                                    if($fullFileName){

                                        $item = preg_replace('/^data:image\/[^;]+;base64,/i', '', $item);

                                        $item = str_replace(' ', '+', $item);

                                        $item = base64_decode($item);

                                        if(file_exists($fullFileName)){

                                            if(@file_put_contents($fullFileName, $item)){

                                                $fileEdit->checkResizeFile($fullFileName);

                                            }

                                        }

                                    }

                                }

                            }

                        }else{

                            $fileData = preg_replace('/^data:image\/[^;]+;base64,/i', '', $fileData);

                            $fileData = str_replace(' ', '+', $fileData);

                            $fileData = base64_decode($fileData);

                            $fullFileName = \AppH::correctPath(\App::FULL_PATH(), \App::config()->WEB('upload_dir')) . $data[$rowName];

                            if(file_exists($fullFileName)){

                                if(@file_put_contents($fullFileName, $fileData)){

                                    $fileEdit->checkResizeFile($fullFileName);

                                }

                            }

                        }

                    }


                }

            }

        }

    }

}