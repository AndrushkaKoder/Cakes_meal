<?php

namespace core\helpers\traites;

trait TizersHelper
{

    public static function checkTizers($data = null){

        static $tizers = [];

        if($tizers && !$data){

            $tempTizers = $tizers;

            $tizers = [];

            return $tempTizers;

        }

        if($data){

            !is_array($data) && $data = json_decode($data, true);

            if($data){

                $noEmpty = false;

                foreach ($data as $value){

                    foreach ((array)$value as $item){

                        if(!empty($item)){

                            $noEmpty = true;

                            break;

                        }

                    }

                }

                if($noEmpty){

                    $tizers = $data;

                }

            }

        }

        return $tizers;

    }

}