<?php

namespace AXP\YaMetrika;

use AXP\YaMetrika\Exception\ClientException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * Class Client
 *
 * @author  Alexander Pushkarev <axp-dev@yandex.com>
 * @link    https://github.com/axp-dev/ya-metrika
 * @package AXP\YaMetrika
 */
class Client
{
    private string $apiEndpoint = 'https://api-metrika.yandex.ru/stat/v1/data';

    private HttpClient $httpClient;

    public function __construct(
        private string $token,
        private string $counterId,
        private array $httpClientOptions = []
    )
    {
        $this->httpClient = new HttpClient(
            $this->buildConfig($this->httpClientOptions)
        );
    }

    private function buildConfig(array $options): array
    {
        return array_merge($options, [
            RequestOptions::HEADERS => [
                'Authorization' => "OAuth {$this->token}"
            ]
        ]);
    }

    private function buildUri(array $params = []): string
    {
        $params['ids'] = $this->counterId;

        return $this->apiEndpoint . '?' . http_build_query(data: $params, arg_separator: '&');
    }

    public function request($params): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->buildUri($params));

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $exception) {
            throw new ClientException($exception->getMessage());
        }
    }
}