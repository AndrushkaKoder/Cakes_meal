<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 31.01.2019
 * Time: 16:01
 */

namespace webQAdminSettings;

use webQTraits\BaseSettings;

class Settings
{

    use BaseSettings;

    private $executeShellScripts = false;

    private $projectTables = [
        'catalog' => ['name' => 'Каталог'],
        'goods' => ['name' => 'Товары'],
        'test' => ['name' => 'TEST'],
        'categories' => ['name' => 'CATEGORIES'],
        'test_position' => ['name' => 'ПОЗИЦИИ'],
        'visitors' => ['name' => 'Пользователи сайта'],
        'fake' => ['name'=>'фейк']
    ];

    private $templateArr = [
        'text' => ['site_url'],
        'text_disabled' => ['disable'],
        'date' => ['date', 'date_modify'],
        'textarea' => ['project_content'],
        'img' => ['dop_img'],
        'gallery_img' => ['dop_gallery_img'],
        'radio' => ['response', 'in_table', 'in_lids', 'visitor_group', 'executed'],
        'select' => ['visitors_modify_id', 'places_id', 'cats_id', 'projects_id'],
        'checkboxlist' => ['categories'],
        'colorpicker' => ['colorpicker2'],
//        'gps' => ['gps_coordinates']
    ];

    private $translate = [
        'site_url' => ['Ссылка на сайт'],
        'date_modify' => ['Дата изменения'],
        'project_content' => ['Описание и ссылка на задачу'],
        'response' => ['Отклик'],
        'in_table' => ['В таблице'],
        'in_lids' => ['В лидах'],
        'visitor_group' => ['Тип пользователя'],
        'visitors_modify_id' => ['Кто изменил'],
        'places_id' => ['Площадка'],
        'executed' => ['Обработано']
    ];

    private $blockNeedle = [

    ];

    private $rootItems = [
//        'name' => '---',
//        'tables' => ['projects']
    ];

    private $tablesUserRootLevel = [
        //'places' => 0,
        'test' => 0
    ];

    private $manyToMany = [
        'test_categories' => ['categories', 'test',]
    ];

    private $radio = [
        'response' => ['Нет', 'Да', 'default' => 'Нет'],
        'in_table' => ['Нет', 'Да', 'default' => 'Нет'],
        'in_lids' => ['Нет', 'Да', 'default' => 'Нет'],
        'visitor_group' => ['Пользователь', 'Редактор', 'default' => 'Пользователь'],
        'executed' => ['Нет', 'Да', 'default' => 'Нет'],
    ];

    private $groupEditRows = [
//        'visible' => [
//            'tables' => [],
//            'connectingRow' => 'parent_id'
//        ],
//        'hit' => [
//            'tables' => [],
//            'connectingRow' => 'parent_id'
//        ],
    ];

}