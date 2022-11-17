<?php

function __autoload($className)
{
    include $className . '.php';
}

//spl_autoload('TinkoffMerchantAPI');
$api = new TinkoffMerchantAPI(
    'TinkoffBankTest',  //Ваш Terminal_Key
    'TinkoffBankTest'   //Ваш Secret_Key
);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="main.css"/>
    <title>Testing Merchant API</title>
</head>
<body>
<h1 align="center">Тестирование MerchantAPI</h1>

<?php

$email = 'test@test.com';
$emailCompany = 'testCompany@test.com';
$phone = '89179990000';

$taxations = [
    'osn'                => 'osn',                // Общая СН
    'usn_income'         => 'usn_income',         // Упрощенная СН (доходы)
    'usn_income_outcome' => 'usn_income_outcome', // Упрощенная СН (доходы минус расходы)
    'envd'               => 'envd',               // Единый налог на вмененный доход
    'esn'                => 'esn',                // Единый сельскохозяйственный налог
    'patent'             => 'patent'              // Патентная СН
];

$paymentMethod = [
    'full_prepayment' => 'full_prepayment', //Предоплата 100%
    'prepayment'      => 'prepayment',      //Предоплата
    'advance'         => 'advance',         //Аванc
    'full_payment'    => 'full_payment',    //Полный расчет
    'partial_payment' => 'partial_payment', //Частичный расчет и кредит
    'credit'          => 'credit',          //Передача в кредит
    'credit_payment'  => 'credit_payment',  //Оплата кредита
];

$paymentObject = [
    'commodity'             => 'commodity',             //Товар
    'excise'                => 'excise',                //Подакцизный товар
    'job'                   => 'job',                   //Работа
    'service'               => 'service',               //Услуга
    'gambling_bet'          => 'gambling_bet',          //Ставка азартной игры
    'gambling_prize'        => 'gambling_prize',        //Выигрыш азартной игры
    'lottery'               => 'lottery',               //Лотерейный билет
    'lottery_prize'         => 'lottery_prize',         //Выигрыш лотереи
    'intellectual_activity' => 'intellectual_activity', //Предоставление результатов интеллектуальной деятельности
    'payment'               => 'payment',               //Платеж
    'agent_commission'      => 'agent_commission',      //Агентское вознаграждение
    'composite'             => 'composite',             //Составной предмет расчета
    'another'               => 'another',               //Иной предмет расчета
];

$vats = [
    'none'  => 'none', // Без НДС
    'vat0'  => 'vat0', // НДС 0%
    'vat10' => 'vat10',// НДС 10%
    'vat20' => 'vat20' // НДС 20%
];

$enabledTaxation = true;
$amount = 1000 * 100;

$receiptItem = [[
    'Name'          => 'product1',
    'Price'         => 200 * 100,
    'Quantity'      => 2,
    'Amount'        => 200 * 2 * 100,
    'PaymentMethod' => $paymentMethod['full_prepayment'],
    'PaymentObject' => $paymentObject['service'],
    'Tax'           => $vats['none']
], [
    'Name'          => 'product2',
    'Price'         => 500 * 100,
    'Quantity'      => 1,
    'Amount'        => 500 * 100,
    'PaymentMethod' => $paymentMethod['full_prepayment'],
    'PaymentObject' => $paymentObject['service'],
    'Tax'           => $vats['vat10']
], [
    'Name'          => 'shipping',
    'Price'         => 100 * 100,
    'Quantity'      => 1,
    'Amount'        => 100 * 100,
    'PaymentMethod' => $paymentMethod['full_prepayment'],
    'PaymentObject' => $paymentObject['service'],
    'Tax'           => $vats['vat20'],
]];

$isShipping = false;

if (!empty($isShipping[2]['Name'] === 'shipping')) {
    $isShipping = true;
}

$receipt = [
    'EmailCompany' => $emailCompany,
    'Email'        => $email,
    'Taxation'     => $taxations['osn'],
    'Items'        => balanceAmount($isShipping, $receiptItem, $amount),
];

function balanceAmount($isShipping, $items, $amount)
{
    $itemsWithoutShipping = $items;

    if ($isShipping) {
        $shipping = array_pop($itemsWithoutShipping);
    }

    $sum = 0;

    foreach ($itemsWithoutShipping as $item) {
        $sum += $item['Amount'];
    }

    if (isset($shipping)) {
        $sum += $shipping['Amount'];
    }

    if ($sum != $amount) {
        $sumAmountNew = 0;
        $difference = $amount - $sum;
        $amountNews = [];

        foreach ($itemsWithoutShipping as $key => $item) {
            $itemsAmountNew = $item['Amount'] + floor($difference * $item['Amount'] / $sum);
            $amountNews[$key] = $itemsAmountNew;
            $sumAmountNew += $itemsAmountNew;
        }

        if (isset($shipping)) {
            $sumAmountNew += $shipping['Amount'];
        }

        if ($sumAmountNew != $amount) {
            $max_key = array_keys($amountNews, max($amountNews))[0];    // ключ макс значения
            $amountNews[$max_key] = max($amountNews) + ($amount - $sumAmountNew);
        }

        foreach ($amountNews as $key => $item) {
            $items[$key]['Amount'] = $amountNews[$key];
        }
    }

    return $items;
}

?>

<?php if (true) : ?>
    <div class="card">
        <h2>Метод Init:</h2>

        <div class="article">
            <?php
            $enabledTaxation = true;

            $params = [
                'OrderId' => 200001,
                'Amount'  => $amount,
                'DATA'    => [
                    'Email'           => $email,
                    'Connection_type' => 'example'
                ],
            ];

            if ($enabledTaxation) {
                $params['Receipt'] = $receipt;
            }

            $api->init($params);
            echo 'Params:';
            ?>

            <p><span class="highlight">Response</span>: <?= $api->response ?></p>

            <?php if ($api->error) : ?>
                <span class="error"><?= $api->error ?></span>
            <?php else: ?>
                <p><span class="highlight">Status</span>: <?= $api->status ?></p>
                <p>
                    <span class="highlight">PaymentUrl</span>:
                    <a href="<?= $api->paymentUrl ?>" target="_blank"><?= $api->paymentUrl ?></a>
                </p>
                <p><span class="highlight">PaymentId</span>: <?= $api->paymentId ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (false) : ?>
    <div class="card">
        <h2>Метод GetState:</h2>

        <div class="article">
            <?php
            $params = [
                'PaymentId' => '2012735',
            ];

            $api->getState($params);
            ?>
            <p><span class="highlight">Response</span>: <?= $api->response ?></p>
            <?php if ($api->error) : ?>
                <p><span class="error"><?= $api->error ?></span></p>
            <?php else: ?>
                <p><span class="highlight">Status</span>: <?= $api->status ?></p>
                <p><span class="highlight">PaymentId</span>: <?= $api->paymentId ?></p>
                <p><span class="highlight">OrderId</span>: <?= $api->orderId ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (false) : ?>
    <div class="card">
        <h2>Метод Confirm:</h2>

        <div class="article">
            <?php
            $params = [
                'PaymentId' => '2014742',
                'Amount'    => 1000 * 100,
            ];

            if ($enabledTaxation) {
                $params['Receipt'] = $receipt;
            }

            $api->confirm($params);
            ?>

            <p><span class="highlight">Response</span>: <?= $api->response ?></p>

            <?php if ($api->error) : ?>
                <span class="error"><?= $api->error ?></span>
            <?php else: ?>
                <p><span class="highlight">Status</span>: <?= $api->status ?></p>
                <p><span class="highlight">PaymentId</span>: <?= $api->paymentId ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (false) : ?>
    <div class="card">
        <h2>Метод AddCustomer</h2>

        <div class="article">
            <?php
            $params = [
                'CustomerKey' => 'TestCustomer',
                'Email'       => $email,
                'Phone'       => $phone,
            ];
            $api->addCustomer($params);
            ?>

            <p><span class="highlight">Response</span>: <?= $api->response ?></p>
            <?php if ($api->error) : ?>
                <p><span class="error"><?= $api->error ?></span></p>
            <?php else: ?>
                <p><span class="highlight">CustomerKey</span>: <?= $api->customerKey ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (true) : ?>
    <div class="card">
        <h2>Метод GetCustomer</h2>

        <div class="article">
            <?php
            $params = [
                'CustomerKey' => 'TestCustomer',
            ];
            $api->getCustomer($params);
            ?>

            <p><span class="highlight">Response</span>: <?= $api->response ?></p>
            <?php if ($api->error) : ?>
                <p><span class="error"><?= $api->error ?></span></p>
            <?php else: ?>
                <p><span class="highlight">CustomerKey</span>: <?= $api->customerKey ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (true) : ?>
    <div class="card">
        <h2>Метод RemoveCustomer</h2>

        <div class="article">
            <?php
            $params = [
                'CustomerKey' => 'TestCustomer',
            ];
            $api->removeCustomer($params);
            ?>

            <p><span class="highlight">Response</span>: <?= $api->response ?></p>
            <?php if ($api->error) : ?>
                <p><span class="error"><?= $api->error ?></span></p>
            <?php else: ?>
                <p><span class="highlight">CustomerKey</span>: <?= $api->customerKey ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (true) : ?>
    <div class="card">
        <h2>Метод GetCardList</h2>

        <div class="article">
            <?php
            $params = [
                'CustomerKey' => 'TestCustomer',
            ];
            $api->getCardList($params);
            ?>

            <p><span class="highlight">Response</span>: <?= $api->response ?></p>
            <?php if ($api->error) : ?>
                <p><span class="error"><?= $api->error ?></span></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (false) : ?>
    <div class="card">
        <h2>Метод RemoveCard</h2>

        <div class="article">
            <?php
            $params = [
                'CardId'      => '869301',
                'CustomerKey' => 'TestCustomer',
            ];
            $api->removeCard($params);
            ?>

            <p><span class="highlight">Response</span>: <?= $api->response ?></p>
            <?php if ($api->error) : ?>
                <p><span class="error"><?= $api->error ?></span></p>
            <?php else: ?>
                <p><span class="highlight">CustomerKey</span>: <?= $api->customerKey ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

</body>
</html>