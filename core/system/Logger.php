<?php

namespace core\system;

use core\traites\Singleton;

class Logger
{

    use Singleton;

    public function writeLog($message, $file = 'log.txt', $event = 'Fault', $rotateLogs = true){

        $dateTime = new \DateTime();

        if($event !== 0)
            $str = $event . ': ' . $dateTime->format('d-m-Y G:i:s') . ' - ' . $message . "\r\n";
        else
            $str = $message . "\r\n";

        $dir = \AppH::correctPathLtrim(\App::FULL_PATH(), \App::WEB('log_dir'));

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

        file_put_contents($dir . $file, $str, FILE_APPEND);

    }

    protected function rotateLogs($dir, $day = 30){

        \AppH::scanDir($dir, function($file, $dir) use($day){

            if((new \DateTime(date('Y-m-d', filemtime($dir . '/' . $file)))) < (new \DateTime())->modify('-' . $day . ' day')){

                @unlink($dir . '/' . $file);

            }

        });

    }

}