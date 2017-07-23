<?php

include_once '../vendor/autoload.php';

$token = 'AQAAAAAQ9ZGPAARZheuxZMcSUkHMi5RcYU-vi1M';
$counter_id = '27170768';
$YaMetrika = new \AXP\YaMetrika\YaMetrika($token, $counter_id);

$data = $YaMetrika->getPreset('traffic', 31);

print_r($data);