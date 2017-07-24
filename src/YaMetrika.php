<?php

namespace AXP\YaMetrika;

use AXP\YaMetrika\Exceptions\YaMetrikaException;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client as GuzzleClient;

class YaMetrika
{
    /**
     * URL API Yandex Metrika
     *
     * @var string
     */
    protected $endPoint = 'https://api-metrika.yandex.ru/stat/v1/data';

    /**
     * Token API
     *
     * @var string
     */
    protected $token;

    /**
     * Id счётчика
     *
     * @var int
     */
    protected $counterId;

    /**
     * Данные из метрики
     *
     * @var array
     */
    protected $data;

    /**
     * Форматированные данные
     *
     * @var array
     */
    protected $formatData;

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
     * Получаем данные
     *
     * @return mixed
     */
    public function get()
    {
        return $this->data;
    }

    /**
     * Получаем данные по шаблону за последние N дней
     *
     * @param string $template
     * @param int    $days
     *
     * @return $this
     */
    public function setPreset($template, $days = 30)
    {
        list($startDate, $endDate) = $this->differenceDate($days);

        $this->setPresetForPeriod($template, $startDate, $endDate);

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
    public function setPresetForPeriod($template, DateTime $startDate, DateTime $endDate, $limit = 10)
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
     * @return array
     */
    public function customQuery($params)
    {
        return $this->query($params);
    }

    /**
     * Отправляем запорос
     *
     * @param array $params
     *
     * @return array
     *
     */
    protected function query($params)
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
    protected function differenceDate($amountOfDays)
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays($amountOfDays);

        return [$startDate, $endDate];
    }
}