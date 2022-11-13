<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 28.06.2019
 * Time: 15:10
 */

namespace core\traites;

trait BaseSettings
{

    use Singleton{
        instance as SingletonInstance;
    }

    private $baseSettings;

    static public function get($property){
        return static::instance()->$property ?? null;
    }

    static public function instance(){

        if(static::$_instance instanceof static){

            return static::$_instance;

        }

        static::SingletonInstance()->baseSettings = static::$_instance->getMainSettings();

        if(static::$_instance->baseSettings && !(static::$_instance instanceof static::$_instance->baseSettings)){

            $baseProperties = static::$_instance->baseSettings->clueProperties(get_class());

            static::$_instance->setProperty($baseProperties);

        }

        return static::$_instance;
    }

    protected function setProperty($properties){
        if($properties){
            foreach ($properties as $name => $property) {
                $this->$name = $property;
            }
        }
    }

    public function clueProperties($class){

        $baseProperties = [];

        foreach($this as $name => $item){
            $property = $class::get($name);

            if(is_array($property) && is_array($item)){

                $baseProperties[$name] = $this->arrayMergeRecursive($this->$name, $property);
                continue;
            }

            if(!$property) $baseProperties[$name] = $this->$name;
        }

        return $baseProperties;
    }

    public function arrayMergeRecursive(){

        $arrays = func_get_args();

        $base = array_shift($arrays);

        foreach($arrays as $array){
            foreach($array as $key => $value){
                if(is_array($value) && isset($base[$key]) && is_array($base[$key])){
                    $base[$key] = $this->arrayMergeRecursive($base[$key], $value);
                }else{
                    if(is_int($key)){
                        if(!in_array($value, $base)) array_push($base, $value);
                        continue;
                    }
                    $base[$key] = $value;
                }
            }
        }

        return $base;

    }

    protected function getMainSettings(){

        $nameSpace = (new \ReflectionClass(static::instance()))->getNamespaceName();

        $mainSettingsClass = $nameSpace . '\\' . 'MainSettings';

        if(class_exists($mainSettingsClass)){

            return $mainSettingsClass::instance();

        }

        return null;

    }

}