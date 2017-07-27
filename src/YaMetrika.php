<?php

namespace AXP\YaMetrika;

use AXP\YaMetrika\Exceptions\YaMetrikaException;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class YaMetrika
 *
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
     */
    function __construct($token, $counterId)
    {
        $this->token = $token;
        $this->counterId = $counterId;
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
            return str_replace('ym:s:', '', $key);
        }, $this->data['query'][$column]);

        return array_combine($queryColumn, $array);
    }

    /**
     * Отправляем запорос
     *
     * @param array $params
     *
     * @return array
     *
     */
    private function query($params)
    {

        $url = $this->endPoint . '?' . http_build_query(array_merge($params, ['ids' => $this->counterId, 'oauth_token' => $this->token]), null, '&');

        try {
            $client = new GuzzleClient();
            $response = $client->request('GET', $url);
            $result = json_decode($response->getBody(), true);

            return $result;
        } catch (YaMetrikaException $e) {
            echo 'Ya Metrika: '.$e->getMessage();
        }

        return [];
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
}