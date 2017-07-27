<?php

include_once '../vendor/autoload.php';

$token = 'AQAAAAAQ9ZGPAARZheuxZMcSUkHMi5RcYU-vi1M';
$counter_id = '44611459';
$YaMetrika = new \AXP\YaMetrika\YaMetrika($token, $counter_id);

$traffic = $YaMetrika->getPreset('traffic', 30)
                      ->format()
                      ->formatData;

print_r( $traffic );