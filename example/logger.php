<?php

use AXP\YaMetrika\Client;
use AXP\YaMetrika\YaMetrika;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use Monolog\Logger;

$stack = HandlerStack::create();
$stack->push(
    Middleware::log(
        new Logger('Logger'),
        new MessageFormatter('{req_body} - {res_body}')
    )
);

$token = getenv('TOKEN');
$counterId = getenv('COUNTER_ID');

$client = new Client($token, $counterId, [
    'handler' => $stack
]);
$metrika = new YaMetrika($client);

$visitors = $metrika->getVisitors();

print_r($visitors->formatData());