<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 07.03.2019
 * Time: 13:13
 */

namespace core\admin\expansion;


use core\base\controller\BaseMethods;
use core\base\controller\Singleton;
use core\base\exceptions\DbException;
use core\base\model\UserModel;
use core\base\settings\Settings;

class UsersExpansion extends Expansion
{

    use Singleton;
    use BaseMethods;

    public function expansion($args = [], $obj = false){

        parent::expansion($args, $obj);

        if($this->className === 'Add' || $this->className === 'Edit'){

            $this->extractCredentials();

            if($this->className === 'Edit' && $this->data['credentials'])
                $this->data['credentials'] = json_decode($this->data['credentials'], true);

        }

        if($this->isPost()){
            if($_POST['id'] == $this->userData['id'] && $_POST['password']){
                UserModel::instance()->logout();
                $this->redirect(PATH.Settings::get('routes')['admin']['alias'] . '/login');
            }
        }

        if(!empty($this->columns['manual_menu_position'])){

            if(empty($this->templateArr['radio'])){

                $this->templateArr['radio'] = [];

            }

            if(!in_array('manual_menu_position', $this->templateArr['radio'])){

                $this->templateArr['radio'][] = 'manual_menu_position';

            }

            if(empty($this->translate['manual_menu_position']) ||
                (!empty($this->translate['manual_menu_position'][0]) && $this->translate['manual_menu_position'][0] === 'manual_menu_position')){

                $this->translate['manual_menu_position'] = ['Режим работы сортировки'];

            }

            if(empty($this->foreignData['manual_menu_position'])){

                $this->foreignData['manual_menu_position'] = ['Автоматический', 'Ручной', 'default' => 'Автоматический'];

            }

        }

        $this->translate['name'] = ['Имя пользователя для отображения'];

    }

    public function extractCredentials(){

        $tables = Settings::get('projectTables');

        $arr = [
            'c' => ['name' => 'Создание', 'id' => 'add'],
            'r' => ['name' => 'Просмотр', 'id' => 'show'],
            'u' => ['name' => 'Редактирование', 'id' => 'edit'],
            'd' => ['name' => 'Удаление', 'id' => 'delete'],
        ];

        if(method_exists($this->model, 'checkDataCreators')){

            $arr = [
                'c' => [
                    'name' => 'Создание',
                    'id' => 'add',
                ],
                'r' => [
                    'name' => 'Просмотр',
                    'id' => 'show',
                    'properties' => [
                        [
                            'name' => 'Владелец',
                            'value' => 1
                        ]
                    ]
                ],
                'u' => [
                    'name' => 'Редактирование',
                    'id' => 'edit',
                    'properties' => [
                        [
                            'name' => 'Владелец',
                            'value' => 1
                        ]
                    ]
                ],
                'd' => [
                    'name' => 'Удаление',
                    'id' => 'delete',
                    'properties' => [
                        [
                            'name' => 'Владелец',
                            'value' => 1
                        ]
                    ]
                ],
            ];

        }

        foreach ($tables as $key => $item){

            $this->foreignData['credentials'][$key]['name'] = $item['name'] ?: $key;

            foreach ($arr as $action => $value){

                $this->foreignData['credentials'][$key]['sub'][$action] = $value;

                if($this->className === 'Add' && $this->columns['credentials'] && $key !== 'users'){

                    $this->data['credentials'][$key][$value['id']] = true;

                }

            }


        }


    }

}