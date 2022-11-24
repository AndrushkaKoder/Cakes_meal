<?php

namespace core\models;

use core\exceptions\AuthException;
use core\system\Logger;
use core\traites\Singleton;

class UserModel extends BaseModel
{

    use Singleton;

    private $cookieName = 'identifier';

    private $cookieAdminName = 'VGEngineData';

    private $userData = [];

    private $error;

    private $userTable = 'visitors';

    private $adminTable = 'users';

    private $blockedTable = 'blocked_access';

    private $defaultPassword = '123';

    public function getAdminTable(){

        return $this->adminTable;

    }

    public function getBlockedTable(){

        return $this->blockedTable;

    }

    public function getLastError(){

        return $this->error;

    }

    public function setAdmin(){

        $this->cookieName = $this->cookieAdminName;

        $this->userTable = $this->adminTable;

        if(!in_array($this->userTable, $this->showTables())){

            $query = 'create table ' . $this->userTable . '
                (
                    id int auto_increment primary key,
                    name varchar(255) null,
                    login varchar(255) null,
                    password varchar(32) null,
                    manual_menu_position tinyint(1) null,
                    credentials text null
                )
                charset = utf8
            ';

            if(!$this->query($query, 'u')){

                exit('Ошибка создания таблицы ' . $this->userTable);

            }

            $this->add($this->userTable, [
                'fields' => ['name' => 'admin', 'login' => 'admin', 'password' => md5($this->defaultPassword)]
            ]);

        }

        if(!in_array($this->blockedTable, $this->showTables())){

            $query = 'create table ' . $this->blockedTable . '
                (
                    id int auto_increment primary key,
                    login varchar(255) null,
                    ip varchar(32) null,
                    trying tinyint(1) null,
                    time datetime null
                )
                charset = utf8
            ';

            if(!$this->query($query, 'u')){

                exit('Ошибка создания таблицы ' . $this->blockedTable);

            }

        }

    }

    public function checkUser($id = false, $admin = false){

        $admin && $this->userTable !== $this->adminTable && $this->setAdmin();

        $method = 'unPackage';

        if($id){

            $this->userData['id'] = $id;

            $method = 'set';

        }

        try{

            $this->$method();

        }catch (AuthException $e){

            $this->error = $e->getMessage();

            !empty($e->getCode()) && Logger::instance()->writeLog($this->error, 'log_user.txt');

            return false;

        }

        return $this->userData;

    }

    private function set(){

        $cookieString = $this->package();

        if($cookieString){

            setcookie($this->cookieName, $cookieString, time() + 60*60*24*365*10, \App::PATH());

            return true;

        }

        throw new AuthException('Ошибка формирования cookie', 1);

    }

    private function package(){

        if(!empty($this->userData['id'])){

            $data['id'] = $this->userData['id'];

            $data['domain'] = $_SERVER['HTTP_HOST'];

            $data['version'] = \App::config()->COOKIE('version');

            $data['cookieTime'] = date('Y-m-d H:i:s');

            return Crypt::instance()->encrypt(json_encode($data));

        }

        throw new AuthException('Некорректный идентификатор пользователя - ' . $this->userData['id'], 1);

    }

    private function unPackage(){

        if(empty($_COOKIE[$this->cookieName]))
            throw new AuthException('Отсутствует cookie пользователя');

        $data = json_decode(Crypt::instance()->decrypt($_COOKIE[$this->cookieName]), true);

        if(empty($data['id']) || empty($data['version']) || empty($data['cookieTime']) || empty($data['domain'])){

            $this->logout();

            throw new AuthException('Некорректные данные в cookie пользователя', 1);

        }

        $this->validate($data);

        $this->userData = $this->get($this->userTable, [
            'where' => ['id' => $data['id']],
            'single' => true
        ]);

        if(!$this->userData){

            $this->logout();
            throw new AuthException('Не найдены данные в таблице ' . $this->userTable . ' по идентификатору ' . $data['id'], 1);

        }

        return true;

    }

    private function validate($data){

        if(!empty(\App::config()->COOKIE('version'))){

            if($data['version'] !== \App::config()->COOKIE('version')){

                $this->logout();
                throw new AuthException('Некорректная версия cookie');

            }

        }

        if(!empty(\App::config()->COOKIE('time'))){

            if((new \DateTime()) > (new \DateTime($data['cookieTime']))->modify(\App::config()->COOKIE('time') . ' minutes')){

                throw new AuthException('Превышено время бездействия пользователя');

            }

        }

        if(!empty($data['domain']) && $data['domain'] !== $_SERVER['HTTP_HOST']){

            throw new AuthException('Несоответствие домена пользователя');

        }

    }

    public function logout(){

        setcookie($this->cookieName, '', 1, \App::PATH());

    }

}