<?php

namespace Hywax\YaMetrika\Service;

use DateTime;
use GuzzleHttp\Psr7\Request;
use Hywax\YaMetrika\Client;
use Hywax\YaMetrika\Exception\ReportServiceException;
use Hywax\YaMetrika\Interface\Transformer;
use Hywax\YaMetrika\Service;
use Hywax\YaMetrika\Transformer\ReportRawTransformer;
use Hywax\YaMetrika\Utils;

class ReportService extends Service
{
    private Transformer $resultTransformer;

    private int|string $counterId;

    public function __construct(Client|array $clientOrConfig = null)
    {
        if (is_array($clientOrConfig)) {
            $this->resultTransformer = $clientOrConfig['resultTransformer'] ?? new ReportRawTransformer();
            $this->counterId = $clientOrConfig['counterId'] ?? 0;
            unset($clientOrConfig['resultTransformer']);
        }

        parent::__construct($clientOrConfig);
    }

    public function getPresent(string $preset, int $days = 30, int $limit = 10): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getPresetForPeriod($preset, $startDate, $endDate, $limit);
    }

    public function getPresetForPeriod(string $preset, DateTime $startDate, DateTime $endDate, int $limit = 10): array
    {
        return $this->call([
            'preset' => $preset,
            'date1' => $startDate->format('Y-m-d'),
            'date2' => $endDate->format('Y-m-d'),
            'limit' => $limit
        ]);
    }

    public function getVisitors(int $days = 30): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getVisitorsForPeriod($startDate, $endDate);
    }

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

    public function getMostViewedPages(int $days = 30, int $limit = 10): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getMostViewedPagesForPeriod($startDate, $endDate, $limit);
    }

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

    public function getBrowsers(int $days = 30, int $limit = 10): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getBrowsersForPeriod($startDate, $endDate, $limit);
    }

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

    public function getUsersSearchEngine(int $days = 30, int $limit = 10): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getUsersSearchEngineForPeriod($startDate, $endDate, $limit);
    }

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

    public function getGeo(int $days = 7, int $limit = 20): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getGeoForPeriod($startDate, $endDate, $limit);
    }

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

    public function getAgeGender($days = 30, int $limit = 20): array
    {
        list($startDate, $endDate) = Utils::getDifferenceDate($days);

        return $this->getAgeGenderForPeriod($startDate, $endDate, $limit);
    }

    public function getAgeGenderForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): array
    {
        return $this->call([
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'preset'     => 'age_gender',
            'limit'      => $limit,
        ]);
    }

    public function getCustomQuery(array $params): array
    {
        return $this->call($params);
    }

    public function getCounterId(): int|string
    {
        return $this->counterId;
    }

    public function setCounterId(int|string $counterId): void
    {
        $this->counterId = $counterId;
    }

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
