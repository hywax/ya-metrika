<?php

namespace Hywax\YaMetrika\Service;

use GuzzleHttp\Psr7\Request;
use Hywax\YaMetrika\Client;
use Hywax\YaMetrika\Interface\Transformer;
use Hywax\YaMetrika\Service;
use Hywax\YaMetrika\Transformer\ReportRawTransformer;

class ReportService extends Service
{
    private Transformer $resultTransformer;

    public function __construct(Client|array $clientOrConfig = null)
    {
        if (is_array($clientOrConfig)) {
            $this->resultTransformer = $clientOrConfig['resultTransformer'] ?? new ReportRawTransformer();
            unset($clientOrConfig['resultTransformer']);
        }

        parent::__construct($clientOrConfig);
    }

    public function getCustomQuery(array $params): array
    {
        return $this->call($params);
    }

    private function call(array $params): array
    {
        $this->getClient()->getLogger()->info('Service Call', ['params' => $params]);

        $url = sprintf('/stat/v1/data?%s', http_build_query($params, '', '&'));
        $request = new Request('GET', $url);

        return $this->resultTransformer->transform(
            $this->getClient()->execute($request)
        );
    }
}
