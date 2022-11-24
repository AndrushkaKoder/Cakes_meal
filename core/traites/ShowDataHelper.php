<?php

namespace core\traites;

trait ShowDataHelper
{

    private function showScriptsStyles($path, $type = 'css', $innerDirectory = ''){

        $template = null;

        if($type === 'css'){

            $template = '<link rel="stylesheet" href="#path#">' . "\n";

        }elseif ($type === 'js'){

            $template = '<script src="#path#"></script>' . "\n";

        }

        if($template){

            $templatePath = \AppH::correctPath(\App::PATH(), \App::config()->WEB('views'), \App::config()->WEB($type), $innerDirectory);

            \AppH::scanDir($path, function ($file) use ($path, $template, $templatePath, $type){

                if(is_dir($path . $file)){

                    if(strtolower($file) === $this->getController()){

                        $this->showScriptsStyles($path . $file, $type, $file);

                    }

                }else{

                    if(!preg_match('/^#/', $file)){

                        echo str_replace('#path#', $templatePath . $file, $template);

                    }

                }

            }, true);

        }

    }

    protected function pagination($pages, $template = ''){
        /*Поиск пораметра Page в адресной строке*/

        $str = $_SERVER['REQUEST_URI'];

        if(preg_match("/(page=\d+)/ui", $str)){
            $str = preg_replace("/(page=\d+)/", '', $str);
        }

        if(preg_match("/(\?&)|(\?amp;)/ui", $str)){
            $str = preg_replace("/(\?&)|(\?amp;)/", '?', $str);
        }

        /*Поиск параметра Page в адресной строке*/

        $basePageStr = $str;

        if(preg_match('/\?(.)?/iu', $str, $matches)){

            if(!preg_match('/&$/', $str) && !empty($matches[1])){

                $basePageStr = $str;
                $str .= '&';

            }else{

                $basePageStr = preg_replace('/(\?$)|(&$)/i', '', $str);

            }

        }else{

            $basePageStr = $str;
            $str .= '?';

        }

        $str .= 'page=';

        $firstPageStr = !empty($pages['first']) ? ($pages['first'] == 1 ? $basePageStr : $str . $pages['first']) : '';

        $backPageStr = !empty($pages['back']) ? ($pages['back'] == 1 ? $basePageStr : $str . $pages['back']) : '';

        $template = $this->render((\AppH::correctPath($this->getViewsPath(), \App::config()->WEB('common')) . 'pagination'));

        if($template){

            $templatesArr = ['first', 'back', 'previous', 'current', 'next', 'forward', 'totalCount', 'last'];

            $notReplaceContent = ['first', 'back', 'forward', 'last'];

            $current = $pages['current'] ?? null;

            foreach ($templatesArr as $key => $element){

                $regExp = '/<\!\-\-' . $element . '\-\->(.+?)<\!\-\-' . $element . '\-\->/is';

                if(!empty($pages[$element]) && preg_match($regExp, $template, $matches)){

                    if($element === 'totalCount' && !empty($current) && $current === $pages['totalCount']){

                        continue;

                    }

                    if(!empty($matches[1])){

                        $regExpLink = '/<a\s+[^>]*>(.+?)<\/a>/is';

                        if(preg_match($regExpLink, $matches[1], $links)){

                            $pages[$element] = (array)$pages[$element];

                            foreach ($pages[$element] as $value){

                                $href = '';

                                switch ($element){

                                    case 'first':
                                        $href = $firstPageStr;
                                        break;

                                    case 'back':
                                        $href = $backPageStr;
                                        break;

                                    case 'previous':
                                        $href = $value == 1 ? $basePageStr : $str . $value;
                                        break;

                                    case 'current':
                                        $href = '';
                                        break;

                                    default:

                                        $href = $str . $value;

                                }

                                if(preg_match('/href\s*=\s*[\'"](.*?)\1/', $links[0])){

                                    $link = preg_replace('/href\s*=\s*([\'"])(.*?)\1/', 'href=$1'. $href .'$1', $links[0]);

                                }else{

                                    $link = preg_replace('/<a\s/', '<a href="'. $href .'" ', $links[0]);

                                }

                                if(!in_array($element, $notReplaceContent)){

                                    $link = preg_replace('/>.*?</is', '>' . $value . '<', $link);

                                }

                                $content = str_replace($links[0], $link, $matches[1]);

                                echo $content;


                            }

                        }

                    }

                }

            }

        }

    }

}