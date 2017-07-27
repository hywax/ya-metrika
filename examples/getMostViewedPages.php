<?php

include_once '../vendor/autoload.php';

$config = require_once 'config.php';
$token = $config['token'];
$counter_id = $config['counter_id'];

$YaMetrika = new \AXP\YaMetrika\YaMetrika($token, $counter_id);

$pages = $YaMetrika->getMostViewedPages(20)
                   ->format()
                   ->formatData;

print_r( $pages );