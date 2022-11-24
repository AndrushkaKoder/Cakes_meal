<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 31.01.2019
 * Time: 16:34
 */

namespace settings;

use core\traites\BaseSettings;

class MainSettings
{

    use BaseSettings;

    private $defaultTemplatePath = '/core/admin/view/include/form_templates/';

    private $expansion = 'core/admin/expansion/';

    private $messages = 'core/base/messages/';

    private $defaultTable = 'users';

    private $menuException = ['users', 'metadata', 'translate_elements'];

    private $GPSCoordinates = '54.51370760575306, 36.26252526395435';

    private $projectTables = [
        'users' => ['name' => 'Пользователи', 'translate' => 'Users'],
    ];

    private $templateArr = [
        'text' => [
            'name', 'login', 'alias', 'price', 'city',
            'title', 'phone', 'email', 'h1', 'work_period',
            'article', 'qty', 'discount', 'not_in_stock',
            'sbrf_username', 'sbrf_password', 'external_payment_status',
            'external_alias', 'el_name',
            'unit', 'catalog_qty'
        ],
        'text_disabled' => [
            'total_sum', 'total_qty', 'discount_price',
        ],
        'date' => ['date'],
        'time' => ['time'],
        'textarea' => [
            'keywords', 'description', 'content', 'short_content',
            'address', 'comment', 'information', 'promo_content',
            'work_time', 'question_answer', 'name_ask', 'promo_content',
        ],
        'select' => [
            'menu_position', 'parent_id', 'orders_id', 'goods_id',
            'payments', 'delivery', 'visitors_id', 'order_status', 'payments', 'delivery_id', 'payments_id'
        ],
        'checkboxlist' => ['filters', 'orders_goods', 'categories', 'credentials', 'prices'],
        'radio' => [
            'visible',
            'in_stock',
            'visitor_type'
        ],
        'addedlist' => ['tizers'],
        'addedlist_img' => ['tizers_img'],
        'file' => ['img', 'document', 'video'],
        'gallery_img' => [
            'gallery_img',
        ],
        'password' => ['password'],
        'colorpicker' => ['colorpicker', 'font_color', 'background_color'],
        'gps' => ['gps_coordinates'],
    ];

    private $fileTemplates = ['img', 'gallery_img', 'document', 'file', 'video'];

    private $translate = [
        'name' => ['Название', 'Не более 250 символов'],
        'credentials' => ['Разрешения пользователей'],
        'description' => ['SEO описание'],
        'keywords' => ['Ключевые слова'],
        'title' => ['Заголовок вкладки браузера (title)'],
        'parent_id' => ['Родительский раздел'],
        'img' => ['Изображение'],
        'gallery_img' => ['Галерея изображений'],
        'short_content' => ['Краткое описание'],
        'content' => ['Описание'],
        'alias' => ['Ссылка чпу'],
        'login' => ['Логин'],
        'password' => ['Пароль'],
        'date' => ['Дата'],
        'menu_position' => ['Позиция вывода'],
        'filters' => ['Фильтры'],
        'colorpicker' => ['Цвет'],
        'visible' => ['Показывать на сайте'],
        'city' => ['Город расположения'],
        'address' => ['Адрес', 'Указывается без города'],
        'phone' => ['Телефон'],
        'email' => ['E-mail'],
        'work_time' => ['Рабочее время', 'Каждый диапазон с новой строки'],
        'comment' => ['Комментарий'],
        'price' => ['Цена'],
        'orders_goods' => ['Заказанные товары'],
        'article' => ['Код товара'],
        'total_sum' => ['Сумма заказа'],
        'payments' => ['Способ оплаты'],
        'delivery' => ['Тип доставки'],
        'total_qty' => ['Количество товаров'],
        'discount_price' => ['Цена с учетом скидки'],
        'orders_id' => ['Номер заказа'],
        'visitors_id' => ['Пользователь сайта'],
        'order_status' => ['Статус заказа'],
        'visitor_type' => ['Тип клиента'],
        'qty' => ['Количество'],
        'goods_id' => ['Название товара на сайте'],
        'discount' => ['Скидка на товары', 'Указывается в процентах'],
        'in_stock' => ['В наличии'],
        'sbrf_username' => ['Имя пользователя для доступа к сбербанку'],
        'sbrf_password' => ['Пароль для доступа к сбербанку'],
        'external_payment_status' => ['Статус онлайн оплаты'],
        'video_content' => ['Ссылка на видеоматериал'],
        'font_color' => ['Цвет текста'],
        'background_color' => ['Цвет фона'],
        'external_alias' => ['Ссылка на страницу'],
        'map' => ['Ссылка на карту'],
        'question_answer' => ['Ответ'],
        'name_ask' => ['Вопрос'],
        'surname' => ['Имя Фамилия'],
        'position' => ['Должность'],
        'categories' => ['Категории товаров'],
        'profession' => ['Профессия'],
        'el_name' => ['Текст элемента'],
        'unit' => ['Единица измерения цены', 'рубли, доллары и т.д'],
        'catalog_qty' => ['Количество элементов на странице'],
        'delivery_id' => ['Способ доставки'],
        'tizers' => ['Тизеры'],
        'tizers_img' => ['Тизеры с изображениями'],
        'document' => ['Файл'],
        'gps_coordinates' => ['GPS координаты']
    ];

    private $radio = [
        'visible' => ['Нет', 'Да', 'default' => 'Да']
    ];

    private $manyToMany = [
        'filters_goods' => ['filters', 'goods'],
    ];

    private $rowAlias = [
        'translate_elements' => 'el_name'
    ];

    private $blockNeedle = [
        'grid' => [],
        'full' => ['content', 'orders_goods', ]
    ];

    private $deepLevel = [
        'filters' => [
            'parent_id' => 0
        ],
        'delivery' => [
            'parent_id' => 0
        ]
    ];

    private $rootItems = [
        'name' => '---',
        'tables' => ['filters', 'catalog']
    ];

    private $tableParameters = 'table_parameters';

    private $validation = [
        'name' => ['empty' => true, 'trim' => true],
        'work_period' => ['empty' => true, 'trim' => true],
        'price' => ['int' => true],
        'min_order_sum' => ['int' => true],
        'old_price' => ['int' => true],
        'login' => ['empty' => true, 'trim' => true],
        'keywords' => ['trim' => true],
        'description' => ['trim' => true],
        'password' => ['crypt' => true]
    ];

    private $unique = [
        'translate_elements' => ['name'],
        'users' => ['login'],
    ];

    private $setWebpImage = true;

}