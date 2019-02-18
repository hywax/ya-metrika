<?php

namespace AXP\YaMetrika;

use AXP\YaMetrika\Exceptions\YaMetrikaException;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;

/**
 * Class YaMetrika
 *
 * @author  Alexander Pushkarev <axp-dev@yandex.com>
 * @link    https://github.com/axp-dev/ya-metrika
 * @package AXP\YaMetrika
 */
class YaMetrika
{
    /**
     * URL API Yandex Metrika
     *
     * @var string
     */
    private $endPoint = 'https://api-metrika.yandex.ru/stat/v1/data';

    /**
     * Token API
     *
     * @var string
     */
    private $token;

    /**
     * Id счётчика
     *
     * @var int
     */
    private $counterId;

    /**
     * Proxy
     *
     * @var string
     */
    private $proxy;

    /**
     * Данные из метрики
     *
     * @var array
     */
    public $data;

    /**
     * Форматированные данные
     *
     * @var array
     */
    public $formatData;

    /**
     * YaMetrika constructor
     *
     * @param string $token
     * @param        $counterId
     * @param string $proxy [user:pass@]host:port
     */
    function __construct($token, $counterId, $proxy = null)
    {
        $this->token = $token;
        $this->counterId = $counterId;
        $this->proxy = $proxy;
    }

    /**
     * Форматируем данные
     *
     * @return $this
     */
    public function format()
    {
        if ( $data = $this->data ) {
            $formatted = [
                'data'   => [],
                'totals' => $this->combineData('metrics', $data['totals']),
                'min'    => $this->combineData('metrics', $data['min']),
                'max'    => $this->combineData('metrics', $data['max']),
            ];

            foreach ($data['data'] as $key => $datum) {
                $formatted['data'][$key] = [
                    'dimensions' => $this->combineData('dimensions', $datum['dimensions']),
                    'metrics'    => $this->combineData('metrics', $datum['metrics']),
                ];
            }

            $this->formatData = $formatted;
        }

        return $this;
    }

    /**
     * Получаем данные по шаблону за последние N дней
     *
     * @param string $template
     * @param int    $days
     * @param int    $limit
     *
     * @return $this
     */
    public function getPreset($template, $days = 30, $limit = 10)
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        $this->getPresetForPeriod($template, $startDate, $endDate, $limit);

        return $this;
    }

    /**
     * Получаем данные по шаблону за выбранный период
     *
     * @param string   $template
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int      $limit
     *
     * @return $this
     */
    public function getPresetForPeriod($template, DateTime $startDate, DateTime $endDate, $limit = 10)
    {
        $params = [
            'preset'    => $template,
            'date1'     => $startDate->format('Y-m-d'),
            'date2'     => $endDate->format('Y-m-d'),
            'limit'     => $limit
        ];

        $this->data = $this->query($params);

        return $this;
    }

    /**
     * Получаем данные о посещаемости за последние N дней
     *
     * @param int $days
     *
     * @return $this
     */
    public function getVisitors($days = 30)
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        $this->getVisitorsForPeriod($startDate, $endDate);

        return $this;
    }

    /**
     * Получаем данные о посещаемости за выбранный период
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     *
     * @return $this
     */
    public function getVisitorsForPeriod(DateTime $startDate, DateTime $endDate)
    {
        $params = [
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'metrics'    => 'ym:s:visits,ym:s:pageviews,ym:s:users',
            'dimensions' => 'ym:s:date',
            'sort'       => 'ym:s:date',
        ];

        $this->data = $this->query($params);

        return $this;
    }

    /**
     * Получаем самые популярные страницы за N дней
     *
     * @param int $days
     * @param int $limit
     *
     * @return $this
     */
    public function getMostViewedPages($days = 30, $limit = 10)
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        $this->getMostViewedPagesForPeriod($startDate, $endDate, $limit);

        return $this;
    }

    /**
     * Получаем самые популярные страницы за выбранный период
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int      $limit
     *
     * @return $this
     */
    public function getMostViewedPagesForPeriod(DateTime $startDate, DateTime $endDate, $limit = 10)
    {
        $params = [
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'metrics'    => 'ym:pv:pageviews',
            'dimensions' => 'ym:pv:URLPathFull,ym:pv:title',
            'sort'       => '-ym:pv:pageviews',
            'limit'      => $limit,
        ];

        $this->data = $this->query($params);

        return $this;
    }

    /**
     * Получаем браузеры пользователей за N дней
     *
     * @param int $days
     * @param int $limit
     *
     * @return $this
     */
    public function getBrowsers($days = 30, $limit = 10)
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        $this->getBrowsersForPeriod($startDate, $endDate, $limit);

        return $this;
    }

    /**
     * Получаем браузеры пользователей за выбранный период
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int      $limit
     *
     * @return $this
     */
    public function getBrowsersForPeriod(DateTime $startDate, DateTime $endDate, $limit = 10)
    {
        $params = [
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'preset'     => 'tech_platforms',
            'dimensions' => 'ym:s:browser',
            'limit'      => $limit,
        ];

        $this->data = $this->query($params);

        return $this;
    }

    /**
     * Получаем посетителей с поисковых систем за N дней
     *
     * @param int $days
     * @param int $limit
     *
     * @return $this
     */
    public function getUsersSearchEngine($days = 30, $limit = 10)
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        $this->getUsersSearchEngineForPeriod($startDate, $endDate, $limit);

        return $this;
    }

    /**
     * Получаем посетителей с поисковых систем за выбранный период
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int      $limit
     *
     * @return $this
     */
    public function getUsersSearchEngineForPeriod(DateTime $startDate, DateTime $endDate, $limit = 10)
    {
        $params = [
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'metrics'    => 'ym:s:users',
            'dimensions' => 'ym:s:searchEngine',
            'filters'    => "ym:s:trafficSource=='organic'",
            'limit'      => $limit,
        ];

        $this->data = $this->query($params);

        return $this;
    }

    /**
     * Получаем посетителей по странам и регионам за N дней
     *
     * @param int $days
     * @param int $limit
     *
     * @return $this
     */
    public function getGeo($days = 7, $limit = 20)
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        $this->getGeoForPeriod($startDate, $endDate, $limit);

        return $this;
    }

    /**
     * Получаем посетителей по странам и регионам за выбранный период
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int      $limit
     *
     * @return $this
     */
    public function getGeoForPeriod(DateTime $startDate, DateTime $endDate, $limit = 20)
    {
        $params = [
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'dimensions' => 'ym:s:regionCountry,ym:s:regionArea',
            'metrics'    => 'ym:s:visits',
            'sort'       => '-ym:s:visits',
            'limit'      => $limit,
        ];

        $this->data = $this->query($params);

        return $this;
    }

    /**
     * Получаем возраст и пол посетителей за N дней
     *
     * @param int $days
     * @param int $limit
     *
     * @return $this
     */
    public function getAgeGender($days = 30, $limit = 20)
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        $this->getAgeGenderForPeriod($startDate, $endDate, $limit);

        return $this;
    }

    /**
     * Получаем возраст и пол посетителей за выбранный период
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int      $limit
     *
     * @return $this
     */
    public function getAgeGenderForPeriod(DateTime $startDate, DateTime $endDate, $limit = 20)
    {
        $params = [
            'date1'      => $startDate->format('Y-m-d'),
            'date2'      => $endDate->format('Y-m-d'),
            'preset'     => 'age_gender',
            'limit'      => $limit,
        ];

        $this->data = $this->query($params);

        return $this;
    }

    /**
     * Отправляем кастомный запрос
     *
     * @param array $params
     *
     * @return $this
     */
    public function customQuery($params)
    {
        $this->data = $this->query($params);

        return $this;
    }

    /**
     * Объединяем массивы для формирования ключей
     *
     * @param string $column
     * @param array $array
     *
     * @return array
     */
    private function combineData($column, $array)
    {
        $queryColumn = array_map(function($key) {
            return str_replace(['ym:s:', 'ym:pv:', 'ym:ad:', 'ym:sp:'], '', $key);
        }, $this->data['query'][$column]);

        return array_combine($queryColumn, $array);
    }

    /**
     * Отправляем запорос
     *
     * @param array $params
     *
     * @return array
     * @throws YaMetrikaException
     */
    private function query($params)
    {
        $url = $this->endPoint . '?' . http_build_query(array_merge($params, ['ids' => $this->counterId]), null, '&');

        try {
            $client = new GuzzleClient($this->getHttpClientParams());
            $response = $client->request('GET', $url);
            $result = json_decode($response->getBody(), true);

            return $result;
        } catch (ClientException $e) {
            throw new YaMetrikaException($e->getMessage());
        }
    }

    /**
     * Вычислям конечную и начальную дату
     *
     * @param $amountOfDays
     *
     * @return array
     */
    private function differenceDate($amountOfDays)
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays($amountOfDays);

        return [$startDate, $endDate];
    }

    /**
     * Получаем массив параметров запроса для HTTP клиента
     *
     * @return array
     */
    private function getHttpClientParams()
    {
        $params = [
            RequestOptions::HEADERS => [
                "Authorization" => "OAuth {$this->token}"
            ]
        ];

        if ($this->proxy) {
            $params[RequestOptions::PROXY] = $this->proxy;
        }

        return $params;
    }
}
