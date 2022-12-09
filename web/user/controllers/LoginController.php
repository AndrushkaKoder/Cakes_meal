<?php

namespace webQApplication\controllers;

use webQModels\UserModel;
use webQApplication\helpers\ValidationHelper;

class LoginController extends BaseUser
{

    use ValidationHelper;

    protected function actionInput(){


        if(!empty($this->parameters['alias'])){
            switch ($this->parameters['alias']){

                case 'registration':

                    $this->registration();

                    break;

                case 'login':

                    $this->login();

                    break;

                case 'logout':
                UserModel::instance()->logout();
                    break;
            }
        }
        
        \WqH::redirect();

    }

    protected function login(){

        $login = \WqH::clearStr($_POST['login'] ?? '');

        $password = \WqH::clearStr($_POST['password'] ?? '');

        if(!$login || !$password){

            $this->sendError('Заполните поля для авторизации');

        }

        $password = md5($password);

        if(!preg_match('/@/', $login)){

            $login = $this->phoneField($login);

        }

        $res = $this->model->get('visitors', [
            'where' => ['email' => $login, 'phone' => $login],
            'condition' => ['OR'],
            'limit' => 1,
            'single' => true
        ]);

        if(!$res || $res['password'] !== $password){

            $message = 'Неправильные логин или пароль';

            if($res && $res['password'] !== $password){

                $message .= '<br><a style="text-decoration: underline; font-size: 18px; color: white" href="' .
                    $this->alias(['login' => 'restore_password', 'user' => urlencode(base64_encode($login))]) . '">' .
                    'Для восстановления пароля перейдите по ссылке' . '</a>';

            }

            $this->sendError($message);

        }

        if(UserModel::instance()->checkUser($res['id'])){

            $this->sendSuccess($this->translateEl('Добро пожаловать') . ' ' . $res['name']);

            return;

        }

        $this->sendError('Произошла ошибка авторизации, обратитесь к администрации сайта');

    }

    protected function registration(){

        $_POST['password'] = trim($_POST['password'] ?? '');

        $_POST['confirm_password'] = trim($_POST['confirm_password'] ?? '');

        if($this->userData && !$_POST['password']){

            unset($_POST['password']);

        }

        if(isset($_POST['password']) && $_POST['password'] !== $_POST['confirm_password']){

            $this->sendError('Пароли не совпадают');

        }


        unset($_POST['confirm_password']);

        $validation = [
            [
                'name' => ['emptyField'],
                'phone' => ['emptyField', 'phoneField', 'numericField'],
                'email' => ['emptyField', 'emailField'],
                'password' => ['emptyField'],
            ],
            [
                'name' => ['emptyField'],
                'phone' => ['emptyField', 'phoneField', 'numericField'],
                'email' => ['emptyField', 'emailField'],
                'password' => ['emptyField'],
                'company_name' => ['emptyField']
            ]

        ];

        $translation = [
            'name' => 'ФИО',
            'phone' => 'Телефон',
            'email' => 'E-mail',
            'company_name' => 'Название компании'
        ];

        $type = !empty($_POST['type']) ? 1 : 0;

        $validationResult = [];

        foreach ($_POST as $key => $item){

            if(!empty($validation[$type][$key])){

                foreach ($validation[$type][$key] as $method){

                    if($this->userData && !$item){

                        unset($_POST[$key]);

                        continue 2;

                    }

                    $_POST[$key] = $item = $this->$method($item, $translation[$key] ?? $key);

                }

                $validationResult[] = $key;

            }

        }

        if(!$this->userData && count($validationResult) !== count($validation[$type])){

            $this->sendError('Не балуйтесь');

        }

        $where = [
            '(phone' => $_POST['phone'],
            ')email' => $_POST['email']
        ];

        $condition = ['OR'];

        if($this->userData){

            $where['!id'] = $this->userData['id'];

            $condition[] = 'AND';

        }

        $res = $this->model->get('visitors', [
            'where' => $where,
            'condition' => $condition,
            'limit' => 1,
            'single' => true
        ]);

        if($res){

            $field = $res['phone'] === $_POST['phone'] ? $this->translateEl('Телефон') : 'Email';

            $this->sendError('Такой ' . $field . ' уже зарегистрирован');

        }

        if(!empty($_POST['password'])){

            $_POST['password'] = md5($_POST['password']);

        }

        if($this->userData){

            $id = $this->userData['id'];

            $this->model->edit('visitors', [
                'where' => ['id' => $id]
            ]);

        }else{

            $id = $this->model->add('visitors', [
                'return_id' => true
            ]);

        }

        if(!empty($id)){

            if(UserModel::instance()->checkUser($id)){

                $this->sendSuccess(($this->userData ? $this->translateEl('Данные обновлены') : $this->translateEl('Решистрация прошла успешно')));

                return;
            }

        }

        $this->sendError('Произошла внутренняя ошибка');

    }
    

}