<?php

namespace web\user\controllers;


class OrderController extends BaseUser
{


    protected function inputData(){


        if(\AppH::isPost()){

            $cart = new CartController();

            $data = $cart->createCartGoods();

            $moreData = null;

            if(method_exists($cart, 'setProjectCartData')){

                $data = $cart->setProjectCartData($data, $moreData);

            }

            $this->order($data);

        }

    }

    public function order($data){

        if(empty($data) || empty($_POST)){

            $this->sendError('Отсутствуют данные для оформления заказа');

        }

        $_defaultPassword = 'qwerty123';

        $validation = [
            'name' => ['emptyField'],
            'phone' => ['emptyField', 'phoneField', 'numericField'],
            'email' => ['emptyField', 'emailField'],
            'delivery_id' => ['emptyField', 'numericField'],
            'payments_id' => ['emptyField', 'numericField'],
        ];

        $translation = [
            'name' => 'ФИО',
            'phone' => 'Телефон',
            'email' => 'E-mail',
            'delivery_id' => 'Способ доставки',
            'payments_id' => 'Способ оплаты'
        ];

        if(!empty($_POST['address']) && is_array($_POST['address'])){

            $addressArr = $_POST['address'];

            $_POST['address'] = '';

            foreach ($addressArr as $item){

                $item = \AppH::clearStr($item);

                if($item){

                    $_POST['address'] .= ($_POST['address'] ? ', ' : '') . $item;

                }

            }

        }

        $order = [];

        $visitor = [];

        $columnsOrders = $this->model->showColumns('orders');

        $columnsVisitors = $this->model->showColumns('visitors');

        $validationResult = [];

        foreach ($_POST as $key => $item){

            if(!empty($validation[$key])){

                foreach ($validation[$key] as $method){

                    $_POST[$key] = $item = $this->$method($item, $translation[$key] ?? $key);

                }

                $validationResult[] = $key;

            }

            if(!empty($columnsOrders[$key])){

                $order[$key] = $item;

            }elseif (!empty($columnsVisitors[$key])){

                $visitor[$key] = $item;

            }

        }

        if(count($validationResult) !== count($validation)){

            $this->sendError('Не балуйтесь');

        }

        if(!empty($validation['delivery_id']) && empty($this->delivery[$_POST['delivery_id']])){

            $this->sendError('Не заполнены данные о способе доставки');

        }

        if(!empty($validation['payments_id']) && empty($this->payments[$_POST['payments_id']])){

            $this->sendError('Не заполнены данные о способе оплаты');

        }

        if(empty($visitor['email']) && empty($visitor['phone'])){

            $this->sendError('Произошла внутренняя ошибка', '', 'Отсутствуют данные для идентификации пользователя. Поля email или phone');

        }

        $visitorWhere = [];

        $visitorCondition = [];

        if(!empty($visitor['email']) && !empty($visitor['phone'])){

            $visitorWhere = [
                '(email' => $visitor['email'],
                ')phone' => $visitor['phone']
            ];

            $visitorCondition = ['OR'];

        }else{

            $visitorKey = !empty($visitor['email']) ? 'email' : 'phone';

            $visitorWhere[$visitorKey] = $visitor[$visitorKey];

        }

        $res = $this->model->get('visitors', [
            'where' => $visitorWhere,
            'condition' => $visitorCondition,
            'limit' => 1,
            'single' => true
        ]);

        if($res){

            $order['visitors_id'] = $res['id'];

        }else{

            $defaultVisitorPassword = $this->set['default_visitors_password'] ?? $_defaultPassword;

            $visitor['password'] = md5($defaultVisitorPassword);

            $order['visitors_id'] = $this->model->add('visitors', [
                'fields' => $visitor,
                'return_id' => true
            ]);

            if(empty($order['visitors_id'])){

                $this->sendError('Произошла внутренняя ошибка', '', 'Ошибка при добавлении пользователя при фофрмлеии заказа');

            }

        }

        $order['total_sum'] = $this->cart['total_sum'];

        $order['total_qty'] = $this->cart['total_qty'];

        $order['total_old_sum'] = $this->cart['total_old_sum'];

        $order['date'] = 'NOW()';

        $baseStatus = $this->model->get('orders_statuses', [
            'fields' => 'id',
            'limit' => 1,
            'order' => 'menu_position',
            'single' => true
        ]);

        $baseStatus && $order['orders_statuses_id'] = $baseStatus['id'];

        $order['id'] = $this->model->add('orders', [
            'fields' => $order,
            'return_id' => true
        ]);

        if(empty($order['id'])){

            $this->sendError('Произошла внутренняя ошибка', '', 'Ошибка при сохранении заказа');

        }

        if(in_array('orders_data', $this->model->showTables()) &&
            !empty($this->model->showColumns('orders_data')['orders_id']) &&
            !empty($this->model->showColumns('orders_data')['data'])){

            $this->model->add('orders_data', [
                'fields' => [
                    'orders_id' => $order['id'],
                    'data' => [
                        'order' => $order,
                        'visitor' => $visitor,
                        'delivery' => $this->delivery,
                        'payments' => $this->payments,
                        'POST' => $_POST,
                        'cart' => $data
                    ]
                ]
            ]);

        }

        $this->setOrdersGoods($data, $order);

        $_SESSION['res']['answer'] = '<div class="success">' . $this->translateEl('Спасибо за заказ<br>В ближайшее время мы свяжемся с Вами для уточнения деталей');

        if(!empty($defaultVisitorPassword)){

            $_SESSION['res']['answer'] .= '<br><br>Ваш пароль для доступа к личному кабинету - <strong>' . $defaultVisitorPassword . '</strong>';

            UserModel::instance()->checkUser($order['visitors_id']);

        }

        $_SESSION['res']['answer'] .= '</div>';

        $this->clearCart();

        $this->sendOrderEmail(['order' => $order, 'visitor' => $visitor, 'goods' => $ordersGoods]);

        $this->checkPaymentsType(['order' => $order, 'visitor' => $visitor, 'goods' => $ordersGoods]);

        $this->redirect();

    }

    protected function setOrdersGoods($data, $order){

        if(in_array('orders_goods', $this->model->showTables())){

            $ordersGoods = [];

            foreach ($data as $key => $item){

                $ordersGoods[$key]['orders_id'] = $order['id'];

                foreach ($item as $field => $value){

                    if(!empty($this->model->showColumns('orders_goods')[$field])){

                        if($this->model->showColumns('orders_goods')['id_row'] === $field){

                            if(!empty($this->model->showColumns('orders_goods')[$this->model->goodsTable . '_' . 'id'])){

                                $ordersGoods[$key][$this->model->goodsTable . '_' . 'id'] = $value;

                            }

                        }else{

                            $ordersGoods[$key][$field] = $value;

                        }

                    }

                }

                if(!empty($item[$this->model->offersTable])){

                    foreach ($item[$this->model->offersTable] as $field => $value){

                        if(!empty($this->model->showColumns('orders_goods')[$this->model->offersTable . '_' . $field])){

                            $ordersGoods[$key][$this->model->offersTable . '_' . $field] = $value;

                        }

                    }

                }

            }

            $this->model->add('orders_goods', [
                'fields' => $ordersGoods
            ]);

        }

        if(!empty($this->model->showColumns($this->model->goodsTable)['popular'])){

            $this->model->edit($this->model->goodsTable, [
                'fields' => ['popular' => 'popular + 1'],
                'where' => ['id' => array_column($data, 'id')],
                'no_ecran' => 'popular'
            ]);

        }

    }

    protected function checkPaymentsType($data){

        if(!empty($data['order']['payments_id']) &&
            !empty($this->payments[$data['order']['payments_id']]['api_username']) &&
            !empty($this->payments[$data['order']['payments_id']]['api_password'])){

            $paymentsSystem = '\\libraries\\payments\\' . (!empty($this->payments[$data['order']['payments_id']]['payments_system']) ?
                    $this->payments[$data['order']['payments_id']]['payments_system'] : 'SBRF');

            if(class_exists($paymentsSystem)){

                $data['payment'] = $this->payments[$data['order']['payments_id']];

                (new $paymentsSystem)->setPayment($data);

            }

        }

    }

    public function getPaymentStatus(){

        $paymenSystem = '\\libraries\\payments\\' . ($this->parameters['system'] ?? 'SBRF');

        if(class_exists($paymenSystem)){

            (new $paymenSystem)->getPaymentStatus();

        }

    }

    protected function sendOrderEmail($arr){

        $sendMail = new SendMail([
            'address' => [$this->set['email'], $arr['visitor']['email']],
            'Subject' => 'Заказ с интернет магазина №' . $arr['order']['id']
        ]);

        foreach ($arr as $key => $item){

            if($key !== 'goods'){

                if($key === 'order'){

                    $item['delivery'] = $item['payments'] = '';

                    if(!empty($item['delivery_id']) && !empty($this->delivery[$item['delivery_id']]['name'])){

                        $item['delivery'] = $this->delivery[$item['delivery_id']]['name'];

                    }

                    if(!empty($item['payments_id']) && !empty($this->payments[$item['payments_id']]['name'])){

                        $item['payments'] = $this->payments[$item['payments_id']]['name'];

                    }

                }

                $sendMail->setTemplateFromArray($item, '', 'order/' . $key);

            }else{

                $goodsTemplate = $sendMail->setTemplate('order_title', $this->translateEl('Состав заказа'), '', 'order/goodsTemplate', false);

                $template = '';

                foreach ($item as $v){

                    if(!empty($v[$this->model->offersTable . '_name'])){

                        $v['name'] .= '(' . $v[$this->model->offersTable . '_name'] . ')';

                    }

                    $template .= $sendMail->setTemplateFromArray($v, '', 'order/' . $key, false);

                }

                $sendMail->setTemplate('goods', $template, $goodsTemplate);

            }

        }

        if(!$sendMail->send()){

            $this->writeLog($sendMail->getLastError(), 'email_error_log.txt');

        }

    }

}