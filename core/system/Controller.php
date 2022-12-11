<?php

namespace webQSystem; // ограничение видимости класса

use webQExceptions\RouteException;
use webQTraits\AliasImgPathesGeneratorHelper;
use webQTraits\BaseMethods;
use webQTraits\ShowDataHelper;

abstract class Controller //абстрактный класс нужен только для наследования от него. Нельзя создать экземпляр абстрактного класса
{

    use ShowDataHelper;
    use AliasImgPathesGeneratorHelper;
    use BaseMethods;

    protected array $parameters = [];

    protected array $templates = [];

    protected bool $skipRenderingTemplates = false;

    protected string $controller = '';

    // пройти по функции request и понять как формируется переменная $method
    public function request(array $arguments, $returnResult = false){

        $this->checkAuth(!(Router::getMode() === 'user'));

        $this->getMessages();
        // в $method формируется строка 'actionInput'
        $this->parameters = $arguments; // принимаем

        if(!empty(\Wq::config()->WEB('default', Router::getMode(), 'commonMethod')) &&
            method_exists($this, \Wq::config()->WEB('default', Router::getMode(), 'commonMethod')) && empty($this->skipCommonData)){

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

        $outputMethod = Router::getOutputMethod();

        if($outputMethod && method_exists($this, $outputMethod)){

            $res = $this->$outputMethod($data);

            if($res){

                if(!is_array($res) || !is_object($res)){

                    exit($res);

                }

                $data = $res;

            }

        }

        $this->renderPage($data); //передаем в renderPage собравшийся метод

    }

    protected function renderPage(?array $data) : void{  //в data ложатся комментарии со страницы

        $layOutPath = \Wq::config()->WEB('layout', Router::getMode(), 'template') ?: \Wq::config()->WEB('layout', 'template'); //шаблон, лежащий в web.php

        if((!$layOutPath || $this->skipRenderingTemplates)){

            echo $data ? json_encode($data) : '';  // если нет пути к шаблону или skipRenderingTemplates = true, выводим массив data, либо его же в json, либо ''

        }else{

            $layOutPathArr = preg_split('/[>\s*<]+/', $layOutPath, 0, PREG_SPLIT_NO_EMPTY); //сюда залетает массив ['header', 'template', 'sidebar', 'footer']

            $fullTemplate = '';

            foreach ($layOutPathArr as $item){ //перебираем массив шаблонов

                $template = $this->createTemplate($item, $data); //формируем шаблон

                $fullTemplate .= $template ?: ''; // выводим шаблон или ''

            }

        }

        if(!empty($_SESSION['res']['answer'])){

            $fullTemplate = preg_replace('/<\/body>/', '<div class="wq-message__wrap">'.$_SESSION['res']['answer'] .'</div></body>', $fullTemplate);

        }

        unset($_SESSION['res']);

        exit($fullTemplate);

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

        $common = \Wq::config()->WEB('common');

        if(is_array($common)){

            $common = $common['directory'] ?? '';

        }

        $common && $common = trim($common, '/') . '/';

        return is_readable($this->getViewsPath() . $common . $this->getController() . '/' . $file . '.php') ?
            $this->getViewsPath() . $common . $this->getController() . '/' . $file . '.php' :
            (is_readable($this->getViewsPath() . $common . $file . '.php') ?
            $this->getViewsPath() . $common . $file :
            (is_readable($this->getViewsPath() . $file . '.php') ? $this->getViewsPath() . $file . '.php' : null));


    }

    protected function render(?string $path = '', ?array $parameters = []) : string{

        $parameters && extract($parameters);

        if(!$path){

            $path = $this->getViewsPath() . $this->getController();

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
            $this->controller = preg_split('/_?controller/', strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1_$2', preg_replace('/^.*?([^\\\]+$)/', '$1', Router::getController()))), 0, PREG_SPLIT_NO_EMPTY)[0];

    }

    protected function getTemplateImg() : string{

        if(!empty(\Wq::config()->WEB('img'))){

            return \WqH::correctPath(\Wq::PATH(), \Wq::config()->WEB('views'), trim(\Wq::config()->WEB('img')));

        }

        return '';

    }

    protected function getStyles() : void{

        $this->showCommonScriptsStyles('css');

        if(!empty(\Wq::config()->WEB('css'))){

            $path = \WqH::correctPath($this->getViewsPath(), trim(\Wq::config()->WEB('css')));

            $this->showScriptsStyles($path);

        }

    }

    protected function getScripts() : void{

        $this->showCommonScriptsStyles('js');

        if(!empty(\Wq::config()->WEB('js'))){

            $path = \WqH::correctPath($this->getViewsPath(), trim(\Wq::config()->WEB('js')));

            $this->showScriptsStyles($path, 'js');

        }

    }

    protected function getViewsPath() : string{

        static $viewsPath = '';

        if($viewsPath){

            return $viewsPath;

        }

        $property = \Wq::config()->WEB('views');

        $property && $viewsPath = \WqH::correctPath(\Wq::FULL_PATH(), $property);

        return $viewsPath;

    }

}