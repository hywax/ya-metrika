<?php

include_once '../vendor/autoload.php';

$config = require_once 'config.php';
$token = $config['token'];
$counter_id = $config['counter_id'];

$YaMetrika = new \AXP\YaMetrika\YaMetrika($token, $counter_id);

$AgeGender = $YaMetrika->getAgeGender()
                       ->format()
                       ->formatData;

print_r( $AgeGender );