<?php

require __DIR__ . '/../src/bootstrap.php';

$merchantClient  = new Wikimart\MerchantAPIClient\Client( 'http://merchant-api.lan', '13473618150931', 'Mh5EDL9TPnm3A1JAIoHM0w' );

// История статусов заказа 787592
$result = $merchantClient->methodGetOrderStatusHistory( 660506 );
if ( $result->getHttpCode() === 200 ) {
    foreach ( $result->getData()->statuses as $status ) {
        echo "{$status->statusTime} - {$status->statusName} ($status->status)" . PHP_EOL;
    }
}

echo PHP_EOL;

// Возможные новые статусы для заказа 787592
$result = $merchantClient->methodGetOrderStatusReasons( 660506 );
if ( $result->getHttpCode() === 200 ) {
    foreach ( $result->getData()->transitions as $transition ) {
        echo $transition->status . PHP_EOL;
        // Возможные причины смены статуса
        foreach ( $transition->reasons as $reason ) {
            echo "\t{$reason->id}) {$reason->name}" . PHP_EOL;
        }
    }
}

echo PHP_EOL;

// Изменение статуса заказа 787592
$result = $merchantClient->methodSetOrderStatus( 660506, $merchantClient::STATUS_REJECTED, 1, 'Ну нету...' );
var_dump( $result );

