<?php

namespace libraries;

use webQTraits\TemplateOutputMethods;

class SendMail2
{

    private $charSet = 'utf-8';

    private $SMTPDebug = 0;

    private $DebugOutput = 'html';

    private $host = 'smtp.yandex.ru';

    private $SMTPSecure = 'tls';

    private $port = 587;

    private $SMTPAuth = true;

    private $username = 'andrusha.kolmakov';

    private $password = 'oxkvfbjlfjsbmaro';

    private $_FromEmail = 'andrusha.kolmakov@yandex.ru';

    private $_FromName = "cakesmeal40.ru";

    private $templatePath = '';

    private $_mailBody = '';

    private $_address = [];

    private $_files = [];

    private $_lastError = '';


    public function __construct($options = []){

        if($options && is_iterable($options)){

            foreach ($options as $name => $value) {

                if(property_exists($this, '_', $name)){

                    $name = '_' . $name;
                }

                $this->$name = $value;
            }

        }

    }

    public function setTemplatesPath($templatesPath = ''){

        if(!$templatesPath){

            $this->_templatesPath = \WqH::correctPath(\Wq::FULL_PATH(), \Wq::config()->WEB('views') ,\Wq::config()->WEB('common')) . 'emailTemplates';

        }else{

            $this->_templatesPath = strpos($templatesPath, \Wq::FULL_PATH())
                ? $templatesPath
                : \Wq::FULL_PATH() . $templatesPath;

        }

        $this->_templatesPath = preg_replace('/\/{2,}/', '/', $this->_templatesPath);

        if(!file_exists($this->_templatesPath)) mkdir($this->_templatesPath, 0777, true);

        !preg_match('/\/$/', $this->_templatesPath) && $this->_templatesPath .= '/';

    }



    public function getTemplate($templateFilePath){

        $template = '';

        if($templateFilePath){

            if(!$this->templatePath){

                $this->setTemplatesPath();
            }

            $templateFilePath = preg_replace('/\/{2,}/', '/', $this->templatePath . $templateFilePath);

            if(!preg_match('/\.[a-z]{1,5}$/i', $templateFilePath)){
                $templateFilePath .= '.php';
            }

            if(is_readable($templateFilePath)){
                $template = file_get_contents($templateFilePath);
            }
        }

    return $template;

    }


    public function setTemplate($name, $value, $template = '', $templateFilePath = '', $setMailBody = true){



    }

}