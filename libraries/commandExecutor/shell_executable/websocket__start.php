<?php

define('VG_ACCESS', true);

require_once realpath(__DIR__ . '/../../../') . '/config.php';
require_once realpath(__DIR__ . '/../../../') . '/core/base/webQSettings/internal_settings.php';
require_once realpath(__DIR__ . '/../../../') . '/libraries/functions.php';

require_once realpath(__DIR__ . '/../../../') . '/vendor/autoload.php';

$socket = \libraries\Websocket::instance();

$webSocket = new \Workerman\Worker('websocket://0.0.0.0:2346');

//$webSocket->count = 4;

$webSocket->onMessage = function($connection, $data) use ($webSocket, $socket){

    //setDocumentRoot();

    $data = json_decode($data);

    $connection->identifier = $data->identifier ?? null;

    if($socket->setConnection($connection)){

        switch (true){

            case !empty($data->chatId) && !empty($data->message):

                $socket->sendMessage($connection, $data->chatId, $data->message);

                break;

        }

        if(!empty($data->chat)){

            $socket->setViewedMessages($connection, $data->chat);

        }else{

            $connection->chat = null;

        }

        $socket->getNotViewedMessages($connection, $self = true);

    }

};

$webSocket->onConnect = function($connection){

    echo "NEW CONN\n";

};

$webSocket->onClose = function ($connection) use ($socket){

    $socket->closeConnection($connection->visitorsId ?? null);

    echo "Close CONNECTION\n";

};

\Workerman\Worker::runAll();
