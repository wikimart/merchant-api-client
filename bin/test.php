<?php

require __DIR__.'/../src/bootstrap.php';

$client = new MerchantAPIClient\Client( 'merchant-api.lan', 'user', 'userpass' );
$request = new MerchantAPIClient\Request( '/api/1.0/orders/123456' );
var_dump( $client->send( $request ) );