<?php

namespace core\helpers\traites;

trait DynamicImportClassesHelper
{

    public static function __callStatic(string $name, array $arguments){

        static $methods = [];

        if(!empty($methods[$name])){

            return $methods[$name]::$name(...$arguments);

        }

        $result = self::scanDir(__DIR__, function ($file, $path) use ($name, $arguments, &$methods){

            $className = str_replace('.php', '', $file);

            if($className !== __CLASS__){

                $res = include_once $path . '/' . $file;

                if($res){

                    preg_match('/(namespace)\s+(.+?);/', file_get_contents($path . '/' . $file), $matches);

                    $nameSpaceClass = '\\';

                    if(!empty($matches[2])){

                        $nameSpaceClass .= trim($matches[2]).'\\';

                    }

                    $nameSpaceClass .= $className;

                    if(method_exists($nameSpaceClass, $name)){

                        $methods[$name] = $nameSpaceClass;

                        return $nameSpaceClass::$name(...$arguments);

                    }

                }
            }

        });

        return $result;

    }

}