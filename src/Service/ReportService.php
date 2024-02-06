<?php

namespace Hywax\YaMetrika\Service;

use DateTime;
use GuzzleHttp\Psr7\Request;
use Hywax\YaMetrika\Client;
use Hywax\YaMetrika\Exception\ClientException;
use Hywax\YaMetrika\Exception\ReportServiceException;
use Hywax\YaMetrika\Interface\Transformer;
use Hywax\YaMetrika\Service;
use Hywax\YaMetrika\Transformer\ReportRawTransformer;
use Hywax\YaMetrika\Utils;

class ReportService extends Service
{
    private Transformer $resultTransformer;

    private int|string $counterId;

    /**
     * @param Client|array<mixed>|null $clientOrConfig
     * @throws ClientException
     */
    public function __construct(Client|array $clientOrConfig = null)
    {
        if (is_array($clientOrConfig)) {
            $this->resultTransformer = $clientOrConfig['resultTransformer'] ?? new ReportRawTransformer();
            $this->counterId = $clientOrConfig['counterId'] ?? 0;
            unset($clientOrConfig['resultTransformer']);
        }

        parent::__construct($clientOrConfig);
    }

    /**
     * Data from preset
     *
     * @param string $preset
     * @param int $days
     * @param int $limit
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getPresent(string $preset, int $days = 30, int $limit = 10): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getPresetForPeriod($preset, $startDate, $endDate, $limit);
    }

    /**
     * Data from preset for period
     *
     * @param string $preset
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $limit
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getPresetForPeriod(string $preset, DateTime $startDate, DateTime $endDate, int $limit = 10): array
    {
        return $this->call([
            'preset' => $preset,
            'date1' => $startDate->format('Y-m-d'),
            'date2' => $endDate->format('Y-m-d'),
            'limit' => $limit
        ]);
    }

    /**
     * Get visitors
     *
     * @param int $days
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getVisitors(int $days = 30): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getVisitorsForPeriod($startDate, $endDate);
    }

    /**
     * Get visitors for period
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getVisitorsForPeriod(DateTime $startDate, DateTime $endDate): array
    {
        return $this->call([
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'metrics'    => 'ym:s:visits,ym:s:pageviews,ym:s:users',
            'dimensions' => 'ym:s:date',
            'sort'       => 'ym:s:date',
        ]);
    }

    /**
     * Get most viewed pages
     *
     * @param int $days
     * @param int $limit
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getMostViewedPages(int $days = 30, int $limit = 10): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getMostViewedPagesForPeriod($startDate, $endDate, $limit);
    }

    /**
     * Get most viewed pages for period
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $limit
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getMostViewedPagesForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): array
    {
        return $this->call([
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'metrics'    => 'ym:pv:pageviews',
            'dimensions' => 'ym:pv:URLPathFull,ym:pv:title',
            'sort'       => '-ym:pv:pageviews',
            'limit'      => $limit,
        ]);
    }

    /**
     * Get browsers
     *
     * @param int $days
     * @param int $limit
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getBrowsers(int $days = 30, int $limit = 10): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getBrowsersForPeriod($startDate, $endDate, $limit);
    }

    /**
     * Get browsers for period
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $limit
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getBrowsersForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): array
    {
        return $this->call([
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'preset'     => 'tech_platforms',
            'dimensions' => 'ym:s:browser',
            'limit'      => $limit,
        ]);
    }

    /**
     * Get users search engine
     *
     * @param int $days
     * @param int $limit
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getUsersSearchEngine(int $days = 30, int $limit = 10): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getUsersSearchEngineForPeriod($startDate, $endDate, $limit);
    }

    /**
     * Get users search engine for period
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $limit
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getUsersSearchEngineForPeriod(DateTime $startDate, DateTime $endDate, $limit = 10): array
    {
        return $this->call([
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'metrics'    => 'ym:s:visits,ym:s:users',
            'dimensions' => 'ym:s:searchEngine',
            'filters'    => "ym:s:trafficSource=='organic'",
            'limit'      => $limit,
        ]);
    }

    /**
     * Get users by country and region
     *
     * @param int $days
     * @param int $limit
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getGeo(int $days = 7, int $limit = 20): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getGeoForPeriod($startDate, $endDate, $limit);
    }

    /**
     * Get users by country and region for period
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $limit
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getGeoForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): array
    {
        return $this->call([
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'dimensions' => 'ym:s:regionCountry,ym:s:regionArea',
            'metrics'    => 'ym:s:visits',
            'sort'       => '-ym:s:visits',
            'limit'      => $limit,
        ]);
    }

    /**
     * Get gender and age of users for
     *
     * @param int $days
     * @param int $limit
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getAgeGender(int $days = 30, int $limit = 20): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getAgeGenderForPeriod($startDate, $endDate, $limit);
    }

    /**
     * Get gender and age of users for period
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $limit
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getAgeGenderForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): array
    {
        return $this->call([
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'preset'     => 'age_gender',
            'limit'      => $limit,
        ]);
    }

    /**
     * Get search phrases
     *
     * @param int $days
     * @param int $limit
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getSearchPhrases(int $days = 30, int $limit = 20): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getSearchPhrasesForPeriod($startDate, $endDate, $limit);
    }


    /**
     * Get search phrases for period
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $limit
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getSearchPhrasesForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): array
    {
        return $this->call([
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'preset'     => 'sources_search_phrases',
            'limit'      => $limit,
        ]);
    }

    /**
     * Get custom query
     *
     * @param array<mixed> $params
     * @return array<mixed>
     * @throws ClientException
     * @throws ReportServiceException
     */
    public function getCustomQuery(array $params): array
    {
        return $this->call($params);
    }

    /**
     * Get the counter ID
     *
     * @return int|string
     */
    public function getCounterId(): int|string
    {
        return $this->counterId;
    }

    /**
     * Set the counter ID
     *
     * @param int|string $counterId
     * @return void
     */
    public function setCounterId(int|string $counterId): void
    {
        $this->counterId = $counterId;
    }

    /**
     * Call the API
     *
     * @param array<mixed> $params
     * @return array<mixed>
     * @throws ReportServiceException
     * @throws ClientException
     */
    private function call(array $params): array
    {
        if (!isset($params['ids'])) {
            $params = array_merge($params, ['ids' => $this->counterId]);
        }

        $this->getClient()->getLogger()->info('Service Call', ['params' => $params]);

        $url = sprintf('/stat/v1/data?%s', http_build_query($params, '', '&'));
        $request = new Request('GET', $url);

        if (!$params['ids']) {
            throw new ReportServiceException('Counter ID is not set');
        }

        return $this->resultTransformer->transform(
            $this->getClient()->execute($request)
        );
    }
}
