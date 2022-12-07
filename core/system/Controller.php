<?php

namespace core\system; // ограничение видимости класса

use core\exceptions\RouteException;
use core\traites\BaseMethods;
use core\traites\ShowDataHelper;

abstract class Controller //абстрактный класс нужен только для наследования от него. Нельзя создать экземпляр абстрактного класса
{

    use ShowDataHelper;
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

        if(!empty(\App::config()->WEB('default', Router::getMode(), 'commonMethod')) &&
            method_exists($this, \App::config()->WEB('default', Router::getMode(), 'commonMethod')) && empty($this->skipCommonData)){

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

        $layOutPath = \App::config()->WEB('layout', Router::getMode(), 'template') ?: \App::config()->WEB('layout', 'template'); //шаблон, лежащий в web.php

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

        $common = \App::config()->WEB('common');

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
            $this->controller = preg_split('/_?controller/', strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1_$2', pathinfo(Router::getController())['filename'])), 0, PREG_SPLIT_NO_EMPTY)[0];

    }

    protected function getTemplateImg() : string{

        if(!empty(\App::config()->WEB('img'))){

            return \AppH::correctPath(\App::PATH(), \App::config()->WEB('views'), trim(\App::config()->WEB('img')));

        }

        return '';

    }

    protected function getStyles() : void{

        $this->showCommonScriptsStyles('css');

        if(!empty(\App::config()->WEB('css'))){

            $path = \AppH::correctPathTrim($this->getViewsPath(), trim(\App::config()->WEB('css'))) . '/';

            $this->showScriptsStyles($path);

        }

    }

    protected function getScripts() : void{

        $this->showCommonScriptsStyles('js');

        if(!empty(\App::config()->WEB('js'))){

            $path = \AppH::correctPathTrim($this->getViewsPath(), trim(\App::config()->WEB('js'))) . '/';

            $this->showScriptsStyles($path, 'js');

        }

    }

    protected function getViewsPath() : string{

        static $viewsPath = '';

        if($viewsPath){

            return $viewsPath;

        }

        $property = \App::config()->WEB('views');

        $property && $viewsPath = \AppH::correctPathLtrim(\App::FULL_PATH(), $property);

        return $viewsPath;

    }

    protected function alias($alias = '', $queryString = ''){

        $str = '';

        if($queryString){

            if(is_array($queryString)){

                foreach ($queryString as $key => $item){

                    if(is_array($item)){

                        $key .= '[]';

                        foreach ($item as $v) $str .= (!$str ? '?' : '&') . $key . '=' . $v;

                    }else{

                        $str .= (!$str ? '?' : '&') . $key . '=' . $item;

                    }


                }

            }else{

                if(strpos($queryString, '?') === false) $str .= '?' . $queryString;
                else $str .= $queryString;

            }

        }

        if(is_array($alias)) {

            $aliasStr = '';

            foreach ($alias as $key => $item) {

                if (!is_numeric($key)) {

                    $aliasStr .= $key . '/' . $item . '/';

                } elseif (is_numeric($key) && $item) {

                    $aliasStr .= $item . '/';

                }

            }

            $alias = trim($aliasStr, '/');

        }

        if(!$alias || $alias === '/') return \App::PATH() . $str;

        if(preg_match('/^https?:\/\//', $alias))
            return $alias . $str;

        if(Router::getMode() === 'admin' && !empty(\App::config()->WEB('alias')) &&
            stripos($alias, \App::config()->WEB('alias')) !== 0){

            $alias = \App::config()->WEB('alias') . '/' . $alias;

        }

        return preg_replace('/\/{2,}/', '/', \App::PATH() . $alias . \App::config()->WEB('end_slash') . $str);

    }

    protected function img($img = '', $tag = false, $set = []){

        if(!$img && is_dir($_SERVER['DOCUMENT_ROOT'] .\AppH::correctPath(\App::$webDirectory, \App::PATH(), \App::config()->WEB('upload_dir')). 'default_images')){

            $dir = scandir($_SERVER['DOCUMENT_ROOT'] .\AppH::correctPath(\App::$webDirectory, \App::PATH(), \App::config()->WEB('upload_dir')) . 'default_images');

            $img = preg_grep('/'.$this->getController().'\./i', $dir) ?: preg_grep('/default\./i', $dir);

            $img && $img = array_shift($img);

        }

        if($img){

            $path = \AppH::correctPath(\App::PATH(), \App::config()->WEB('upload_dir')) . $img;

            $class = isset($set['class']) && $set['class'] ?
                ' class="' . (is_array($set['class']) ? implode(' ', $set['class']) : $set['class']) . '" ' : '';

            $alt = isset($set['alt']) && $set['alt'] ? ' alt="' . $set['alt'] . '" ' : '';

            $title = isset($set['title']) && $set['title'] ? ' title="' . $set['title'] . '" ' : '';

            $style = isset($set['style']) && $set['style'] ?
                ' style="' . (is_array($set['style']) ? implode(';', $set['style']) : $set['style']) . '" ' : '';

            $data = '';

            if(isset($set['data']) && $set['data']){

                if(is_array($set['data'])){

                    foreach($set['data'] as $key => $item){

                        if(stripos($key, 'data-') === false)
                            $data .= 'data-';

                        $data .= $key . '="' . $item . '"';

                    }

                }else{

                    if(!preg_match('/^\s*data[^=]+=/i', $set['data']))
                        $data = 'data-attribute="' . $set['data'] . '"';
                    else $data = $set['data'];

                }

            }

            if(!$tag)
                return $path;

            echo '<img src="' . $path . '"' . $alt . $title . $class . $style . ' ' . $data . ' >';

        }

        return '';

    }

}