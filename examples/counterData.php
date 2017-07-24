<?php

include_once '../vendor/autoload.php';

$token = 'AQAAAAAQ9ZGPAARZheuxZMcSUkHMi5RcYU-vi1M';
$counter_id = '44611459';
$YaMetrika = new \AXP\YaMetrika\YaMetrika($token, $counter_id);

$traffic = $YaMetrika->setPreset('sources_summary', 30)
                     ->get();

print_r( $traffic );