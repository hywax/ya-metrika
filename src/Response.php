<?php

namespace AXP\YaMetrika;

use AXP\YaMetrika\Exception\FormatException;

/**
 * Class Response
 *
 * @author  Alexander Pushkarev <axp-dev@yandex.com>
 * @link    https://github.com/axp-dev/ya-metrika
 * @package AXP\YaMetrika
 */
class Response
{
    private array $combineKeys = [
        'ym:s:', 'ym:pv:', 'ym:ad:', 'ym:sp:'
    ];

    private array $cachedFormat = [];

    public function __construct(private array $data)
    {
    }

    public function formatData(): array
    {
        if ($this->cachedFormat) {
            return $this->cachedFormat;
        }

        try {
            $formatted = [
                'data'   => [],
                'totals' => $this->combineData('metrics', $this->data['totals']),
                'min'    => $this->combineData('metrics', $this->data['min']),
                'max'    => $this->combineData('metrics', $this->data['max']),
            ];

            foreach ($this->data['data'] as $key => $datum) {
                $formatted['data'][$key] = [
                    'dimensions' => $this->combineData('dimensions', $datum['dimensions']),
                    'metrics'    => $this->combineData('metrics', $datum['metrics']),
                ];
            }

            $this->cachedFormat = $formatted;

            return $formatted;
        } catch (\Exception $exception) {
            throw new FormatException($exception->getMessage());
        }
    }

    public function rawData(): array
    {
        return $this->data;
    }

    public function customFormat(callable $callback): array
    {
        return $callback($this->data);
    }

    private function combineData(string $column, array $array): array
    {
        $queryColumn = array_map(function($key) {
            return str_replace($this->combineKeys, '', $key);
        }, $this->data['query'][$column]);

        return array_combine($queryColumn, $array);
    }
}