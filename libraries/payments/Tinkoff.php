<?php

namespace libraries\payments;

use core\base\controller\BaseMethods;
use core\user\model\Model;
use libraries\payments\TinkoffMerchantAPI\TinkoffMerchantAPI;

class Tinkoff
{

    use BaseMethods;

    static function checkPayment(){

        $_this = new self;

        if(!empty($_GET['orderId'])){

            $set = Model::instance()->get('settings', [
                'order' => 'id',
                'limit' => 1,
                'single' => true
            ]);

            $orderId = $_this->clearNum($_GET['orderId']);

            $status = Model::instance()->get('orders', [
                'fields' => ['external_payments_id', 'payments_from_page'],
                'where' => ['external_payments_status' => false, '!external_payments_id' => false, 'id' => $orderId],
                'single' => true
            ]);

            if($status && !empty($set['sbrf_username']) && !empty($set['sbrf_password'])){

                $terminalKey = trim($set['sbrf_username']);

                $secretKey = trim($set['sbrf_password']);

                $tinkoff = new TinkoffMerchantAPI($terminalKey, $secretKey);

                $hashData = array(
                    'TerminalKey' => $terminalKey,
                    'PaymentId'   => $status['external_payments_id'],
                    'Password'    => $secretKey,
                );

                ksort($hashData);

                $hash = implode($hashData);

                $params = [
                    'TerminalKey' => $terminalKey,
                    'PaymentId'   => $status['external_payments_id'],
                    'Token'    => hash('sha256', $hash),
                ];

                $tinkoff->getState($params);

                if(!$tinkoff->error){

                    $STATUSES = [
                        'REJECTED' => '<strong style="color: red">Ваша оплата не произведена</strong>',
                        'CONFIRMED' => '<strong style="color: green">Оплата произведена успешно</strong>'
                    ];

                    if(!empty($STATUSES[$tinkoff->status])){

                        $message = $STATUSES[$tinkoff->status];

                    }else{

                        $message = 'Статус полаты ' . $tinkoff->status;

                    }

                    $_SESSION['res']['answer'] = !empty($_SESSION['res']['answer']) ? $_SESSION['res']['answer'] . "<br>" . $message : $message;

                    Model::instance()->edit('orders', [
                        'fields' => ['external_payments_status' => $tinkoff->status],
                        'where' => ['id' => $orderId]
                    ]);

                    $path = !empty($status['payments_from_page']) ? $status['payments_from_page'] : 'cart';

                    $_this->redirect($path);

                }

            }

        }

    }



    static function setPayment($order){

        $_this = new self;

        if(empty($order['orderId']) && empty($order['Amount'])){

            $order = $_this->createCorrectTinkoffData($order);

        }

        if(!empty($order['orderId']) && !empty($order['Amount'])){

            $settings = Model::instance()->get('settings', [
                'order' => 'id',
                'limit' => 1,
                'single' => true
            ]);

            if(!empty($settings['sbrf_username']) && !empty($settings['sbrf_password'])){

                $terminalKey = trim($settings['sbrf_username']);

                $secretKey = trim($settings['sbrf_password']);

                $tinkoff = new TinkoffMerchantAPI($terminalKey, $secretKey);

                $orderId = (int)$order['orderId'];

                $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];

                $params = [
                    'OrderId' => $orderId,
                    'Amount'  => $order['Amount'] * 100,
                    'SuccessURL' => $url . PATH . 'external-payments/tinkoff' . END_SLASH . '?orderId=' . $orderId,
                    'FailURL' => $url . PATH . 'external-payments/tinkoff' . END_SLASH . '?orderId=' . $orderId,
                ];

                unset($order['orderId'], $order['Amount']);

                if(!empty($order['Receipt']['Items'])){

                    foreach ($order['Receipt']['Items'] as $key => $item){

                        if(empty($item['Price'])){

                            unset($order['Receipt']['Items'][$key]);

                            continue;

                        }

                        $order['Receipt']['Items'][$key]['Price'] = round($item['Price'], 2) * 100;

                        if(empty($item['Quantity'])){

                            $order['Receipt']['Items'][$key]['Quantity'] = $item['Quantity'] = 1;

                        }

                        if(empty($item['Amount'])){

                            $item['Amount'] = round($item['Price'], 2) * $item['Quantity'];

                        }

                        $order['Receipt']['Items'][$key]['Amount'] = $item['Amount'] * 100;

                        if(empty($item['Tax'])){

                            $order['Receipt']['Items'][$key]['Tax'] = 'none';

                        }

                    }

                    if(empty($order['Receipt']['Items'])){

                        unset($order['Receipt']);

                    }else{

                        if(empty($order['Receipt']['Email'])){

                            $order['Receipt']['Email'] = !empty($_POST['email']) && preg_match('/@/', $_POST['email']) ? $_POST['email'] : $settings['email'];

                        }

                        if(empty($order['Receipt']['Phone'])){

                            $order['Receipt']['Phone'] = !empty($_POST['phone']) ? $_POST['phone'] : $settings['phone'];

                        }

                    }

                }

                if(!empty($order)){

                    $params = array_merge($params, $order);

                }

                $tinkoff->init($params);

                if(!$tinkoff->error){

                    Model::instance()->edit('orders', [
                        'fields' => ['external_payments_id' => $tinkoff->paymentId],
                        'where' => ['id' => $orderId]
                    ]);

                    $_this->redirect($tinkoff->paymentUrl);

                }

            }

        }

    }

    protected function createCorrectTinkoffData($order){

        $result = [];

        if(!empty($order['id'])){

            $result['orderId'] = $order['id'];

        }

        if(!empty($order['total_sum'])){

            $result['Amount'] = $order['total_sum'];

        }

        if(!empty($order['goods'])){

            $result['Receipt'] = [
                'Taxation' => 'envd',
                'Items' => []
            ];

            foreach ($order['goods'] as $item){

                if(empty($item['name']) || empty($item['price'])){

                    continue;

                }

                $oneItem = [];

                $oneItem['Name'] = $item['name'];

                $oneItem['Price'] = $item['price'];

                if(!empty($item['qty'])){

                    $oneItem['Quantity'] = $item['qty'];

                }

                $result['Receipt']['Items'][] = $oneItem;

            }

        }

        return $result;

    }

}