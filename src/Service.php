<?php

namespace Hywax\YaMetrika;

use TypeError;

abstract class Service
{
    private Client $client;

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

    protected function getClient(): Client
    {
        return $this->client;
    }
}
