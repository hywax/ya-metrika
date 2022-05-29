<?php

use AXP\YaMetrika\Client;
use AXP\YaMetrika\YaMetrika;

$token = getenv('TOKEN');
$counterId = getenv('COUNTER_ID');
$proxy = getenv('PROXY');

$client = new Client($token, $counterId, [
    'proxy' => $proxy
]);
$metrika = new YaMetrika($client);

$visitors = $metrika->getVisitors();

print_r($visitors->formatData());