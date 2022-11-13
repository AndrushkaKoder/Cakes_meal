<?php

namespace libraries\payments;

use core\base\controller\BaseMethods;
use core\user\model\Model;

class SBRF
{

    use BaseMethods;

    public $returnUrl = 'cart/checkpayments/system/SBRF';

    public function setPayment($data){

        $order = $data['order'] ?? null;

        $goods = $data['goods'] ?? null;

        $payment = $data['payment'] ?? null;

        if(empty($order['id'])){

            exit('Ошибка формирования онлайн оплаты. Отсутствует идентификатор заказа');

        }

        if(!($payment = $this->validatePayments($order, $payment))){

            return;

        }

        $sum = (int)$order['total_sum'] * 100;

        $parameters = [];

        $parameters['orderNumber'] = $order['id'];

        if($goods){

            $sum = 0;

            $counter = 0;

            foreach ($goods as $item){

                $counter++;

                $price = (int)($item['price'] * 100);

                $amount = $item['qty'] * $price;

                $code = $item[Model::instance()->goodsTable . '_id'] ?? '';

                $code = !empty($item['code']) ? $item['code'] :
                            (!empty($item['article']) ? $item['article'] : $code);

                $code .= !empty($item[Model::instance()->offersTable . '_id']) ? '--' . $item[Model::instance()->offersTable . '_id'] : '';

                !$code && $code = $counter;

                $cart[] = [
                    'positionId' => $counter,
                    'name' => $item['name'],
                    'quantity' => array(
                        'value' => $item['qty'],
                        'measure' => 'шт'
                    ),
                    'itemAmount' => $amount,
                    'itemCode' => $code,
                    'tax' => array(
                        'taxType' => 0,
                        'taxSum' => 0
                    ),
                    'itemPrice' => $price,
                ];

                $sum += $amount;

            }

            $parameters['orderBundle'] = json_encode(
                array(
                    'cartItems' => array(
                        'items' => $cart
                    )
                ),
                JSON_UNESCAPED_UNICODE
            );

        }

        $parameters['amount'] = $sum;

        $parameters['userName'] = trim($payment['api_username']);

        $parameters['password'] = trim($payment['api_password']);

        if(!empty($payment['api_return_url'])){

            $this->returnUrl = trim($payment['api_return_url']);

        }

        if(!preg_match('/^\s*https?:\/\//', $this->returnUrl)){

            $this->returnUrl = (!empty($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http') . '://' . $_SERVER['SERVER_NAME'] . PATH . ltrim($this->returnUrl, ' /');

        }

        $mode = array_key_exists('api_mode', $payment) ? (int)$payment['api_mode'] : 1;

        $res = $this->sendSbrfRequest($parameters, $mode);

        !isset($_SESSION['res']['answer']) && $_SESSION['res']['answer'] = '';

        if(!$res || !($result = json_decode($res, true))){

            $_SESSION['res']['answer'] .= '<br>Ошибка при формировании данных для онлайн оплаты. Свяжитесь с администрацией сайта';

            $this->writeLog('Получен некорректный результат от платежной системы. Ответ от платежной системы - ' . $res);

        }

        if(!empty($result['formUrl']) && !empty($result['orderId'])){

            Model::instance()->edit('orders', [
                'fields' => ['external_payment_id' => $result['orderId']],
                'where' => ['id' => $order['id']]
            ]);

            $this->redirect($result['formUrl']);

        }else{

            $_SESSION['res']['answer'] = 'Ошибка при формировании данных для онлайн оплаты. Свяжитесь с администрацией сайта';

            $this->writeLog('Сообщение от платежной системы - ' . $result['errorMessage']);

        }

    }

    public function getPaymentStatus(){

        $extOrderId = $this->clearStr($_GET['orderId']);

        if(!$extOrderId){

            exit('Куку охибка!!!');

        }

        $order = Model::instance()->get('orders', [
            'where' => ['external_payment_id' => $extOrderId, 'external_payment_status' => false],
            'single' => true,
            'limit' => 1
        ]);

        if(!$order || empty($order['payments_id'])){

            exit('Куку охибка!!!');

        }

        if(!($payment = $this->validatePayments($order, null))){

            $this->writeLog('Некорректные данные по системе оплаты для заказа - ' . $extOrderId .
                "\nЗаказ - " . print_r($order, true), 'payments_error_log.txt');

            return;

        }

        $mode = array_key_exists('api_mode', $payment) ? (int)$payment['api_mode'] : 1;

        $parameters = [
            'orderId' => $order['external_payment_id'],
            'userName' => trim($payment['api_username']),
            'password' => trim($payment['api_password'])
        ];

        $tempResult = $this->sendSbrfRequest($parameters, $mode, 'statusAction');

        if(empty($_SESSION['res']['answer'])){

            $_SESSION['res']['answer'] = '';

        }

        if(!$tempResult || !($result = json_decode($tempResult, true))){

            $_SESSION['res']['answer'] .= '<br><div>Ошибка при формировании данных для онлайн оплаты. Свяжитесь с администрацией сайта</div>';

            $this->writeLog('Получен некорректный результат от платежной системы. Ответ от платежной системы - ' . $tempResult);

        }else{

            $_SESSION['res']['answer'] .= '<br><div class="success">
                                            Статус оплаты - <strong style="color: #bb1616; font-size: 20px">' . $result['errorMessage'] . '</strong>
                                            </div>';

            Model::instance()->edit('orders', [
                'fields' => ['external_payment_status' => $result['errorMessage'], 'external_payment_date' => 'NOW()'],
                'where' => ['id' => $order['id']]
            ]);

        }

        $path = !empty($order['payment_from_page']) ? $order['payment_from_page'] : PATH . 'cart' . END_SLASH;

        $this->redirect($path);

    }

    protected function sendSbrfRequest($parameters = [], $workMode = 1, $action = 'registerAction'){

        $url = !$workMode ? 'https://3dsec.sberbank.ru/payment/rest/' : 'https://securepayments.sberbank.ru/payment/rest/';

        $registerAction = 'register.do';

        $statusAction = 'getOrderStatusExtended.do';

        if($action === 'registerAction'){

            $parameters['returnUrl'] = $this->returnUrl ?: $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . PATH . 'external-payments/sbrf' . END_SLASH;

        }

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $link = $url . $$action . '?' . http_build_query($parameters);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $link);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec($ch);

        curl_close($ch);

        return $output;

    }

    protected function validatePayments($order, $payments){

        if(empty($payment) && !empty($order['payments_id'])){

            $payment = Model::instance()->get('payments', [
                'where' => ['id' => $order['payments_id']],
                'single' => true
            ]);

        }

        if(!$payment){

            $this->writeLog("Отсутствуют данные о системе оплаты для заказа \r\n" . print_r($order, true), 'payments_error_log.txt');

            return false;

        }

        if(empty($payment['api_username']) || empty($payment['api_password'])){

            $this->writeLog("Отсутствуют данные подключения к платежной системе \r\n" . print_r($payment, true), 'payments_error_log.txt');

            return false;

        }

        return $payment;

    }

}