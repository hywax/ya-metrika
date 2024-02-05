<?php

include '../../vendor/autoload.php';

use Hywax\YaMetrika\Exception\ClientException;
use Hywax\YaMetrika\Service\ReportService;
use Hywax\YaMetrika\Transformer\ReportDataTransformer;

try {
    $reportService = new ReportService([
        'token' => getenv('TOKEN'),
        'counterId' => getenv('COUNTER'),
        'resultTransformer' => new ReportDataTransformer(),
    ]);

    $visitors = $reportService->getVisitors();

    print_r($visitors);
} catch (ClientException $e) {
    echo $e->getMessage();
}
