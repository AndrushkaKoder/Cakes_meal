<?php

namespace libraries;

class SendMail
{
    private $CharSet = 'utf-8';

    private $SMTPDebug = 0;

    private $Debugoutput = 'html';

    private $Host = 'smtp.yandex.ru';

    private $SMTPSecure = 'tls';

    private $Port = 587;

    private $SMTPAuth = true;

    private $Username = 'ustpromailer';

    private $Password = 'qzwxec11';

    private $_FromEmail = 'ustpromailer@yandex.ru';

    private $_FromName = null;

    private $_templatesPath = '';

    private $_mailBody = '';

    private $_address = [];

    private $_files = [];

    private $_lastError = '';

    public function __construct($options = []){

        if($options){

            foreach ($options as $name => $value){

                if(property_exists($this, '_' . $name)){

                    $name = '_' . $name;

                }

                $this->$name = $value;


            }

        }

    }

    public function setTemplatesPath($templatesPath = ''){

        if(!$templatesPath){

            $this->_templatesPath= $_SERVER['DOCUMENT_ROOT'] . PATH . TEMPLATE . 'include/emailTemplates';

        }else{

            $this->_templatesPath = strpos($templatesPath, $_SERVER['DOCUMENT_ROOT'] . PATH)
                ? $templatesPath
                : $_SERVER['DOCUMENT_ROOT'] . PATH . $templatesPath;

        }

        $this->_templatesPath = preg_replace('/\/{2,}/', '/', $this->_templatesPath);

        if(!file_exists($this->_templatesPath)) mkdir($this->_templatesPath, 0777, true);

        !preg_match('/\/$/', $this->_templatesPath) && $this->_templatesPath .= '/';

    }

    public function setTemplate($name, $value, $template = '', $templateFilePath = '', $setMailBody = true){

        $checkTemplate = $this->getTemplate($templateFilePath);

        $checkTemplate && $template = $checkTemplate;

        $template = preg_replace('/#' . preg_replace('/\-/', '\-', preg_quote($name)) . '#/', $value, $template);

        if($setMailBody){

            $this->setMailBody($template);

        }

        return $template;

    }

    public function setTemplateFromArray($arr, $template = '', $templateFilePath = '', $setTemplate = true){

        if(is_iterable($arr)){

            foreach ($arr as $name => $value){

                if(!$template && ($checkTemplate = $this->getTemplate($templateFilePath))){

                    $template = $checkTemplate;

                    $templateFilePath = '';

                }

                $template = $this->setTemplate($name, $value, $template, $templateFilePath, false);

            }

            if($setTemplate)
                $this->setMailBody($template);

        }

        return $template;

    }

    public function getTemplate($templateFilePath){

        $template = '';

        if($templateFilePath){

            if(!$this->_templatesPath){

                $this->setTemplatesPath();

            }

            $templateFilePath = preg_replace('/\/{2,}/', '/', $this->_templatesPath . $templateFilePath);

            if(!preg_match('/\.[a-z]{1,5}$/i', $templateFilePath)){

                $templateFilePath .= '.php';

            }

            if(is_readable($templateFilePath)){

                $template = file_get_contents($templateFilePath);

            }

        }

        return $template;

    }

    public function setMailBody($template){

        $this->_mailBody .= $template;

    }

    public function addFile($path, $name){

        $this->_files[$path] = $name;

    }

    public function send($email = '', $subject = ''){

        require_once $_SERVER['DOCUMENT_ROOT'] . PATH . 'libraries/PHPMailer/PHPMailerAutoload.php';

        $sender = new \PHPMailer;

        $sender->isSMTP();

        if(!is_array($this->_address)){

            $this->_address = (array)$this->_address;

        }

        if($email){

            foreach ((array)$email as $item){

                $this->_address[] = $item;

            }

        }

        foreach ($this as $name => $value){

            if(!preg_match('/^_/', $name)){

                $sender->$name = $value;

            }

        }

        $sender->setFrom($this->_FromEmail, $this->_FromName ?: $_SERVER['HTTP_HOST']);

        foreach ($this->_address as $item){

            $sender->addAddress($item);

        }

        if(!empty($this->_files)){

            foreach ($this->_files as $path => $name){

                $sender->addAttachment($path, $name);

            }

        }

        if(!empty($subject)){

            $sender->Subject = $subject;

        }

        if(!preg_match('/<\/html>\s*$/', $this->_mailBody)){

            $this->_mailBody = '<html><body>' . $this->_mailBody . '</body></html>';

        }

        $sender->msgHTML($this->_mailBody);

        $this->_lastError = '';

        if($sender->send()){

            $this->_mailBody = '';

            return true;

        }

        $this->_lastError = $sender->ErrorInfo;

        return false;

    }

    public function getError(){

        return $this->_lastError;

    }

}