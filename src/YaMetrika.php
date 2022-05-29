<?php

namespace AXP\YaMetrika;

use DateTime;
use DateInterval;

/**
 * Class YaMetrika
 *
 * @author  Alexander Pushkarev <axp-dev@yandex.com>
 * @link    https://github.com/axp-dev/ya-metrika
 * @package AXP\YaMetrika
 */
class YaMetrika
{
    public function __construct(private Client $client)
    {
    }

    public function getPreset(string $preset, int $days = 30, int $limit = 10): Response
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        return $this->getPresetForPeriod($preset, $startDate, $endDate, $limit);
    }

    public function getPresetForPeriod(string $preset, DateTime $startDate, DateTime $endDate, int $limit = 10): Response
    {
        $params = [
            'preset' => $preset,
            'date1' => $startDate->format('Y-m-d'),
            'date2' => $endDate->format('Y-m-d'),
            'limit' => $limit
        ];

        return new Response($this->client->request($params));
    }

    public function getVisitors(int $days = 30): Response
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        return $this->getVisitorsForPeriod($startDate, $endDate);
    }

    public function getVisitorsForPeriod(DateTime $startDate, DateTime $endDate): Response
    {
        $params = [
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'metrics'    => 'ym:s:visits,ym:s:pageviews,ym:s:users',
            'dimensions' => 'ym:s:date',
            'sort'       => 'ym:s:date',
        ];

        return new Response($this->client->request($params));
    }

    public function getMostViewedPages(int $days = 30, int $limit = 10): Response
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        return $this->getMostViewedPagesForPeriod($startDate, $endDate, $limit);
    }

    public function getMostViewedPagesForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): Response
    {
        $params = [
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'metrics'    => 'ym:pv:pageviews',
            'dimensions' => 'ym:pv:URLPathFull,ym:pv:title',
            'sort'       => '-ym:pv:pageviews',
            'limit'      => $limit,
        ];

        return new Response($this->client->request($params));
    }

    public function getBrowsers(int $days = 30, int $limit = 10): Response
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        return $this->getBrowsersForPeriod($startDate, $endDate, $limit);
    }

    public function getBrowsersForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 10): Response
    {
        $params = [
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'preset'     => 'tech_platforms',
            'dimensions' => 'ym:s:browser',
            'limit'      => $limit,
        ];

        return new Response($this->client->request($params));
    }

    public function getUsersSearchEngine(int $days = 30, int $limit = 10): Response
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        return $this->getUsersSearchEngineForPeriod($startDate, $endDate, $limit);
    }

    public function getUsersSearchEngineForPeriod(DateTime $startDate, DateTime $endDate, $limit = 10): Response
    {
        $params = [
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'metrics'    => 'ym:s:visits,ym:s:users',
            'dimensions' => 'ym:s:searchEngine',
            'filters'    => "ym:s:trafficSource=='organic'",
            'limit'      => $limit,
        ];

        return new Response($this->client->request($params));
    }

    public function getGeo(int $days = 7, int $limit = 20): Response
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        return $this->getGeoForPeriod($startDate, $endDate, $limit);
    }

    public function getGeoForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): Response
    {
        $params = [
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'dimensions' => 'ym:s:regionCountry,ym:s:regionArea',
            'metrics'    => 'ym:s:visits',
            'sort'       => '-ym:s:visits',
            'limit'      => $limit,
        ];

        return new Response($this->client->request($params));
    }

    public function getAgeGender($days = 30, int $limit = 20): Response
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        return $this->getAgeGenderForPeriod($startDate, $endDate, $limit);
    }

    public function getAgeGenderForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): Response
    {
        $params = [
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'preset'     => 'age_gender',
            'limit'      => $limit,
        ];

        return new Response($this->client->request($params));
    }

    public function getSearchPhrases($days = 30, int $limit = 20): Response
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        return $this->getSearchPhrasesForPeriod($startDate, $endDate, $limit);
    }

    public function getSearchPhrasesForPeriod(DateTime $startDate, DateTime $endDate, int $limit = 20): Response
    {
        $params = [
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'preset'     => 'sources_search_phrases',
            'limit'      => $limit,
        ];

        return new Response($this->client->request($params));
    }

    public function customQuery(array $params): Response
    {
        return new Response($this->client->request($params));
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    private function differenceDate($amountOfDays = 30): array
    {
        $endDate = new DateTime();
        $startDate = (new DateTime())->sub(new DateInterval("P{$amountOfDays}D"));

        return [$startDate, $endDate];
    }
}