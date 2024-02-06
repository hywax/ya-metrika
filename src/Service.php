<?php

namespace Hywax\YaMetrika;

use Hywax\YaMetrika\Exception\ClientException;
use TypeError;

abstract class Service
{
    private Client $client;

    /**
     * @param Client|array<string,mixed>|null $clientOrConfig
     * @throws ClientException
     */
    public function __construct(Client|array $clientOrConfig = null)
    {
        if ($clientOrConfig instanceof Client) {
            $this->client = $clientOrConfig;
        } elseif (is_array($clientOrConfig)) {
            $this->client = new Client($clientOrConfig);
        } else {
            throw new TypeError('constructor must be array or instance of Hywax\YaMetrika\Client');
        }
    }

    /**
     * Get HTTP client
     *
     * @return Client
     */
    protected function getClient(): Client
    {
        return $this->client;
    }
}
