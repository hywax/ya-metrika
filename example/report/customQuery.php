<?php

include '../../vendor/autoload.php';

use Hywax\YaMetrika\Exception\ClientException;
use Hywax\YaMetrika\Service\ReportService;
use Hywax\YaMetrika\Transformer\ReportDataTransformer;

try {
    $reportService = new ReportService([
        'token' => getenv('TOKEN'),
        'resultTransformer' => new ReportDataTransformer(),
    ]);

    $visitsWithoutRobots = $reportService->getCustomQuery([
        'ids' => getenv('COUNTER'),
        'date1' => '2024-01-01',
        'date2' => '2024-01-31',
        'metrics' => 'ym:s:visits',
        'dimensions' => 'ym:s:searchEngine',
        'filters' => "ym:s:trafficSource=='organic' AND ym:s:isRobot=='No'",
    ]);

    print_r($visitsWithoutRobots);
} catch (ClientException $e) {
    echo $e->getMessage();
}
