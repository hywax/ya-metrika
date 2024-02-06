<?php

namespace Hywax\YaMetrika;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use Monolog\Handler\StreamHandler as MonologStreamHandler;
use Monolog\Logger;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use Hywax\YaMetrika\Exception\ClientException;

class Client
{
    public const API_BASE_PATH = 'https://api-metrika.yandex.ru';

    /**
     * @var array<string, mixed>
     */
    private array $config;

    private ClientInterface $httpClient;

    private LoggerInterface $logger;

    /**
     * @param array<string, mixed> $config
     * @throws ClientException
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'token' => null,
        ], $config);

        if (empty($this->config['token'])) {
            throw new ClientException('Token is required');
        }

        $this->logger = $this->createDefaultLogger();
        $this->httpClient = $this->createDefaultHttpClient();
    }

    /**
     * Set AUTH token
     *
     * @param string $token
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->config['token'] = $token;
    }

    /**
     * Set logger
     *
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * Get logger
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Set HTTP client
     *
     * @param ClientInterface $httpClient
     * @return void
     */
    public function setHttpClient(ClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get HTTP client
     *
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    /**
     * Execute HTTP request
     *
     * @param RequestInterface $request
     * @return array<mixed>
     * @throws ClientException
     */
    public function execute(RequestInterface $request): array
    {
        try {
            $request = $request->withHeader('Authorization', sprintf('OAuth %s', $this->config['token']));
            $response = $this->httpClient->send($request);
        } catch (ClientExceptionInterface $e) {
            $this->getLogger()->error($e->getMessage());

            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * Create default logger
     *
     * @return LoggerInterface
     */
    protected function createDefaultLogger(): LoggerInterface
    {
        $logger = new Logger('ya-metrika-php-client');
        $handler = new MonologStreamHandler('php://stderr', Logger::NOTICE);
        $logger->pushHandler($handler);

        return $logger;
    }

    /**
     * Create default HTTP client
     *
     * @return ClientInterface
     */
    protected function createDefaultHttpClient(): ClientInterface
    {
        return new GuzzleClient([
            'base_uri' => self::API_BASE_PATH,
        ]);
    }
}
