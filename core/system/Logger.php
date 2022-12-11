<?php

namespace webQSystem;

use webQTraits\Singleton;

class Logger
{

    use Singleton;

    public function writeLog($message, string $file = 'log.txt', ?string $event = 'Fault', bool $rotateLogs = true) : void{

        $dateTime = new \DateTime();

        $str = '';

        foreach ((array)$message as $item){

            $str .= ($event ? $event . ': ' . $dateTime->format('d-m-Y G:i:s.v') . "\r\n" : '') . $item . "\r\n";

        }


        $dir = \WqH::correctPath(\Wq::FULL_PATH(), \Wq::config()->WEB('log_dir'));

        $extraDir = preg_split('/\//', $file);

        if(count($extraDir) > 1){

            $file = array_pop($extraDir);

            $dir = \WqH::correctPath($dir, implode('/', $extraDir));

        }

        if(!is_dir($dir)){

            mkdir($dir, 0777, true);

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

    protected function rotateLogs(string $dir, int $day = 30) : void{

        \WqH::scanDir($dir, function($file, $dir) use($day){

            if((new \DateTime(date('Y-m-d', filemtime($dir . '/' . $file)))) < (new \DateTime())->modify('-' . $day . ' day')){

                @unlink($dir . '/' . $file);

            }

        });

    }

}