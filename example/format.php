<?php

use AXP\YaMetrika\Client;
use AXP\YaMetrika\YaMetrika;

$token = getenv('TOKEN');
$counterId = getenv('COUNTER_ID');

$client = new Client($token, $counterId);
$metrika = new YaMetrika($client);

$visitors = $metrika->getVisitors();
$totals = $visitors->customFormat(function ($data) {
    return $data['totals'];
});

print_r($totals);