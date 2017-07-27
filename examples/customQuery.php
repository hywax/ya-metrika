<?php

include_once '../vendor/autoload.php';

$config = require_once 'config.php';
$token = $config['token'];
$counter_id = $config['counter_id'];

$YaMetrika = new \AXP\YaMetrika\YaMetrika($token, $counter_id);

$data = [
    'date1'     => Carbon\Carbon::yesterday()->format('Y-m-d'),
    'date2'     => Carbon\Carbon::today()->format('Y-m-d'),
    'metrics'   => 'ym:s:visits',
];

$visits = $YaMetrika->customQuery($data)
                    ->data;

print_r( $visits );