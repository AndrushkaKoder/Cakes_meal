<?php

namespace core\system; // ограничение видимости класса

use core\exceptions\RouteException;
use core\traites\ShowDataHelper;

abstract class Controller //абстрактный класс нужен только для наследования от него. Нельзя создать экземпляр абстрактного класса
{

    use ShowDataHelper;

    protected array $parameters = [];

    protected array $templates = [];

    protected bool $skipRenderingTemplates = false;

    protected string $controller = '';

    // пройти по функции request и понять как формируется переменная $method
    public function request(array $arguments, $returnResult = false){

        // в $method формируется строка 'actionInput'
        $this->parameters = $arguments; // принимаем

        if(!empty(\App::getWebConfig('default', Router::getMode(), 'commonMethod')) && method_exists($this, \App::getWebConfig('default', Router::getMode(), 'commonMethod')) && empty($this->skipCommonData)){

            $this->commonData();

        }

        $method = Router::getInputMethod();

        if(!method_exists($this, $method)){ //существует ли метод класса

            throw new RouteException('Method ' . $method . ' doesn`t exists in ' . (new \ReflectionClass($this))->getName());
            // если нет, создаём новое исключение - метода не существует
        }

        $data = $this->$method();

        if($returnResult){

            return $data;

        }

        $this->renderPage($this->$method()); //передаем в renderPage собравшийся метод

    }

    protected function renderPage(?array $data){  //в data ложатся комментарии со страницы

        $layOutPath = \App::getWebConfig('layout', Router::getMode(), 'template') ?: \App::getWebConfig('layout', 'template'); //шаблон, лежащий в web.php

        if((!$layOutPath || $this->skipRenderingTemplates)){

            echo $data ? json_encode($data) : '';  // если нет пути к шаблону или skipRenderingTemplates = true, выводим массив data, либо его же в json, либо ''

        }else{

            $layOutPathArr = preg_split('/[>\s*<]+/', $layOutPath, 0, PREG_SPLIT_NO_EMPTY); //сюда залетает массив ['header', 'template', 'sidebar', 'footer']

            foreach ($layOutPathArr as $item){ //перебираем массив шаблонов

                $template = $this->createTemplate($item, $data); //формируем шаблон

                echo $template ?: ''; // выводим шаблон или ''

            }

        }

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

        $common = \App::getWebConfig(Router::getMode(), 'common') ?: \App::getWebConfig('common');

        $common && $common = trim($common, '/') . '/';

        return is_readable($this->getViewsPath() . $common . '/' . $file . '.php') ? $this->getViewsPath() . $common . $file :
            (is_readable($this->getViewsPath() . $file . '.php') ? $this->getViewsPath() . $file . '.php' : null);

    }

    protected function render(?string $path = '', ?array $parameters = []) : string{

        $parameters && extract($parameters);

        if(!$path){

            $path = $this->getViewsPath() . \App::controller();

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
            $this->controller = preg_split('/_?controller/', strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1_$2', pathinfo(Router::getController())['filename'])), 0, PREG_SPLIT_NO_EMPTY)[0];

    }

    protected function getStyles() : void{

        if(!empty(\App::getWebConfig('css'))){

            $path = \AppH::correctPathTrim($this->getViewsPath(), trim(\App::getWebConfig('css'))) . '/';

            $this->showScriptsStyles($path);

        }

    }

    protected function getTemplateImg(){

        if(!empty(\App::getWebConfig('img'))){

            return \App::getWebPath() .\App::config()->WEB('views') .'/'. trim(\App::config()->WEB('img'), '/') . '/';

        }

    }

    protected function getScripts(){

        if(!empty(\App::getWebConfig('js'))){

            $path = \AppH::correctPathTrim($this->getViewsPath(), trim(\App::getWebConfig('js'))) . '/';

            $this->showScriptsStyles($path, 'js');

        }

    }

    protected function getViewsPath() : string{

        static $viewsPath = '';

        if($viewsPath){

            return $viewsPath;

        }

        $property = \App::getWebConfig(Router::getMode(), 'views') ?: \App::getWebConfig('views');

        $property && $viewsPath = preg_replace('/\/{2,}/', '/', \App::FULL_PATH() . '/' . trim($property, '/') . '/');

        return $viewsPath;

    }

}