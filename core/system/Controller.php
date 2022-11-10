<?php

namespace core\system; // ограничение видимости класса

use core\exceptions\RouteException;
use core\traites\BaseMethods;

abstract class Controller //абстрактный класс нужен только для наследования от него. Нельзя создать экземпляр абстрактного класса
{

    use BaseMethods;

    protected array $parameters = [];

    protected array $templates = [];

    protected bool $skipRenderingTemplates = false;

    protected string $controller = '';

    // пройти по функции request и понять как формируется переменная $method
    public function request(array $arguments) : void{


        $method = 'action' . ucfirst(str_ireplace('action', '', \App::WEB('default', 'user', 'method'))); // ucfirst приводит первый символ строки к верхнему регистру. app::web - web.php
        // в $method формируется строка 'actionInput'
        $this->parameters = $arguments; // принимаем

        if(method_exists($this, 'commonData') && empty($this->skipCommonData)){
            $this->commonData();
        }

        if(!method_exists($this, $method)){ //существует ли метод класса

            throw new RouteException('Method ' . $method . ' doesn`t exists in ' . (new \ReflectionClass($this))->getName());
            // если нет, создаём новое исключение - метода не существует
        }

        $this->renderPage($this->$method()); //передаем в renderPage собравшийся метод

    }

    protected function renderPage(?array $data){  //в data ложатся комментарии со страницы


        $layOutPath = \App::WEB('layout', 'template'); //шаблон, лежащий в web.php

        if((!$layOutPath || $this->skipRenderingTemplates)){

            echo $data ? json_encode($data) : '';  // если нет пути к шаблону или skipRenderingTemplates = true, выводим массив data, либо его же в json, либо ''

        }else{

            $layOutPathArr = preg_split('/[>\s*<]+/', $layOutPath, 0, PREG_SPLIT_NO_EMPTY); //сюда залетает массив ['header', 'template', 'sidebar', 'footer']
    $a=1;
            foreach ($layOutPathArr as $item){ //перебираем массив шаблонов

                $template = $this->createTemplate($item, $data); //формируем шаблон

                echo $template ?: ''; // выводим шаблон или ''

            }

        }

        unset($_SESSION['res']);

        exit;

    }

    private function createTemplate(string $file, ?array $data) : ?string{

        $template = $this->templates[$file] ?? ($this->templates[$this->getController()] ?? null);

        if(isset($template)){

            return $template;

        }

        $template = $this->searchTemplateFile($file) ?: $this->searchTemplateFile($this->getController());

        $template && $template = $this->render($template, $data); //if($template){$template = $this->render($template, $data)}

        return $template;

    }

    private function searchTemplateFile(string $file) : ?string{ //Ожидаем аргумент string, а возвращаем string || null

        $viewsPath = preg_replace('/\/{2,}/', '/', \App::getWebPath(true) . trim(\App::WEB('views'), '/') . '/');

        $common = \App::WEB('common') ? trim(\App::WEB('common'), '/') . '/' : '';

        return is_readable($viewsPath . $common . '/' . $file . '.php') ? $viewsPath . $common . $file :
            (is_readable($viewsPath . $file . '.php') ? $viewsPath . $file . '.php' : null);

    }

    protected function render(?string $path = '', ?array $parameters = []) : string{

        $parameters && extract($parameters);
        $a=1;

        if(!$path){

            $path = \App::getWebPath(true) . trim(\App::WEB('views'), '/') . '/' . $this->getController();

        }

        $path = preg_replace('/\/{2,}/', '/', str_replace('\\', '/', preg_replace('/\.php\s*$/', '', $path)));

        ob_start();

        if(!file_exists($path . '.php')){

            throw new RouteException($path . ' does not exists');

        }

        include $path . '.php';

        return ob_get_clean();

    }

    protected function getController() : string{

        return $this->controller ?:
            $this->controller = preg_split('/_?controller/', strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1_$2', (new \ReflectionClass($this))->getShortName())), 0, PREG_SPLIT_NO_EMPTY)[0];

    }

    protected function getStyles() : void{

        if(!empty(\App::WEB('css'))){

            $path = trim(\App::WEB('views'), '/') . '/' . trim(\App::WEB('css'), '/') . '/';

            $this->showScriptsStyles($path);

        }

    }

    protected function getTemplateImg(){
        if(!empty(\App::WEB('img'))){
            return \App::getWebPath() .\App::WEB('views') .'/'. trim(\App::WEB('img'), '/') . '/';
        }
    }



    protected function getScripts(){

        if(!empty(\App::WEB('js'))){

            $path = trim(\App::WEB('views'), '/') . '/' . trim(\App::WEB('js'), '/') . '/';

            $this->showScriptsStyles($path, 'js');

        }

    }

    private function showScriptsStyles($path, $type = 'css'){

        $template = null;

        if($type === 'css'){

            $template = '<link rel="stylesheet" href="#path#">' . "\n";

        }elseif ($type === 'js'){

            $template = '<script src="#path#"></script>' . "\n";

        }

        if($template){

            \AppH::scanDir(\App::getWebPath(true) . $path, function ($file) use ($path, $template){

                echo str_replace('#path#', \App::getWebPath() . $path . $file, $template);

            });

        }

    }

}