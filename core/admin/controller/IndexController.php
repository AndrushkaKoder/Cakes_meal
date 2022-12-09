<?php

namespace webQAdmin\controller;


use webQAdmin\model\Model;
use core\base\settings\Settings;

use libraries\FileEdit;

class IndexController extends BaseAdmin
{

    protected function inputData(){

//        $data = scandir($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR);
//        unset($data[0], $data[1]);
//
//        $fileEdit = new FileEdit();
//
//        foreach($data as $item){
//            $ext = substr($item, strrpos($item, '.') + 1);
//            if($ext == 'jpg' || $ext == 'png' || $ext == 'gif'){
//                $info = getimagesize($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $item);
//                $wh = '';
//                if($info[0] >= $info[1] && $info[0] > 800){
//                    $wh = 'width';
//                }elseif($info[1] > 800){
//                    $wh = 'height';
//                }
//                if($wh){
//                    $fileEdit->createThumbnail($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $item, ['resize' => $wh . '|800']);
//                }
//            }
//        }

        $redirect = $this->alias([\Wq::config()->WEB('alias'), 'show']);

        \WqH::redirect($redirect);

    }

}