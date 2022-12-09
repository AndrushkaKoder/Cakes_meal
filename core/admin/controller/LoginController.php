<?php

namespace webQAdmin\controller;

use webQModels\Crypt;
use webQModels\UserModel;
use webQSystem\Controller;
use webQSystem\Logger;

class LoginController extends Controller {

    protected $model;

    private $redirect = true;

    private $checkToken = true;

    protected function inputData(){

        if(isset($this->parameters['logout'])){

            $this->checkAuth(true);

            $user_log = 'Выход пользователя - ' . $this->userData['name'];

            Logger::instance()->writeLog($user_log, 'log_user.txt', 'LogoutUser');

            UserModel::instance()->logout();

            \WqH::redirect(\Wq::PATH());

        }

        $this->model = UserModel::instance();

        $this->model->setAdmin();

        if(\WqH::isPost()){

            if($this->checkToken){

                if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['token']){

                    exit('Куку охибка!!!');

                }

            }

            $ip_user = filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP) ?:
                (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP) ?: @$_SERVER['REMOTE_ADDR']);

            $time_clean = new \DateTime();

            $time_clean->modify("-" . \Wq::config()->WEB('admin', 'block_time') . " hour");

            $this->model->delete($this->model->getBlockedTable(), [
                'where' => ['<time' => $time_clean->format("Y-m-d H:i:s")],
            ]);

            $trying = $this->model->get($this->model->getBlockedTable(), [
                'fields' => ['trying'],
                'where' => ['ip' => $ip_user],
                'limit' => '1',
                'single' => true
            ]);

            $trying = !empty($trying) ? \WqH::clearNum($trying['trying']) : 0;

            $success = 0;

            if($_POST['login'] && $_POST['password']
                && $trying < \WqH::clearNum(\Wq::config()->WEB('admin', 'logging_errors_count'))){

                $login = \WqH::clearStr($_POST['login']);

                $password = Crypt::pwd(\WqH::clearStr($_POST['password']));

                $userData = $this->model->get($this->model->getAdminTable(), [
                    'fields' => ['id', 'name'],
                    'where' => [
                        'login' => $login,
                        'password' => $password,
                    ],
                    'limit' => 1,
                    'single' => true
                ]);

                if(!$userData){

                    $method = 'add';

                    $where = [];

                    if($trying){

                        $method = 'edit';

                        $where['ip'] = $ip_user;

                    }

                    $this->model->$method($this->model->getBlockedTable(), [
                        'fields' => ['login' => $login, 'ip' => $ip_user, 'time' => 'NOW()', 'trying' => ++$trying],
                        'where' => $where
                    ]);

                    $error = "Некорректная попытка входа пользователя - ip адрес - ". $ip_user .
                        "\r\nЛогин - " . $_POST['login'];

                }else{

                    if(!$this->model->checkUser($userData['id'])){

                        $error = $this->model->getLastError();

                    }else{

                        $error = 'Вход пользователя - '.$login;

                        $success = 1;

                    }

                }

            }elseif($trying >= \WqH::clearNum(\Wq::config()->WEB('admin', 'logging_errors_count'))){

                $this->model->logout();

                $error = "Превышено максимальное количество попыток ввода пароля - " . $ip_user;

            }else{

                $error = "Заполните обязательные поля";

            }

            $_SESSION['res']['answer'] = $success ? '<div class="success">Добро пожаловать ' . $userData['name'] . '</div>' :
                '<div class="error">' . (preg_split('/\s*\-/', $error, 2, PREG_SPLIT_NO_EMPTY)[0]) . '</div>';

            Logger::instance()->writeLog($error, 'log_user.txt', 'AccessUser');

            $path = null;

            $success && $path = \WqH::correctPath(\Wq::PATH(), \Wq::config()->WEB('admin', 'alias'));

            if($this->redirect){

                \WqH::redirect($path);

            }

            return $success;

        }

    }

    protected function outputData(){

        return $this->render('', [
            'adminPath' => \Wq::config()->WEB('admin', 'alias')
        ]);

    }

    public function APIAuth(){

        $this->redirect = false;

        $this->checkToken = false;

        $_SERVER['REQUEST_METHOD'] = 'POST';

        $_POST['login'] = $_SERVER['PHP_AUTH_USER'] ?? '';

        $_POST['password'] = $_SERVER['PHP_AUTH_PW'] ?? '';

        return $this->inputData();

    }
}