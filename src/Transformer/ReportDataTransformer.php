<?php

namespace Hywax\YaMetrika\Transformer;

use Hywax\YaMetrika\Exception\ReportTransformerException;
use Hywax\YaMetrika\Interface\Transformer;

class ReportDataTransformer implements Transformer
{
    /**
     * Keys to combine
     *
     * @var array<string>
     */
    private array $combineKeys = [
        'ym:s:', 'ym:pv:', 'ym:ad:', 'ym:sp:'
    ];

    /**
     * Get the value of combineKeys
     *
     * @return array<string>
     */
    public function getCombineKeys(): array
    {
        return $this->combineKeys;
    }

    /**
     * Set the value of combineKeys
     *
     * @param array<string> $combineKeys
     *
     * @return void
     */
    public function setCombineKeys(array $combineKeys): void
    {
        $this->combineKeys = $combineKeys;
    }

    /**
     * Transform the data
     *
     * @param mixed $data
     * @return array{data: array<mixed>, totals: array<mixed>, min: array<mixed>, max: array<mixed>}
     * @throws ReportTransformerException
     */
    public function transform($data): array
    {
        try {
            $formatted = [
                'data'   => [],
                'totals' => $this->combineData('metrics', $data['totals'], $data),
                'min'    => $this->combineData('metrics', $data['min'], $data),
                'max'    => $this->combineData('metrics', $data['max'], $data),
            ];

            foreach ($data['data'] as $key => $datum) {
                $formatted['data'][$key] = [
                    'dimensions' => $this->combineData('dimensions', $datum['dimensions'], $data),
                    'metrics'    => $this->combineData('metrics', $datum['metrics'], $data),
                ];
            }

            return $formatted;
        } catch (\Exception $e) {
            throw new ReportTransformerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Combine the data
     *
     * @param string $column
     * @param array<mixed> $array
     * @param array<mixed> $data
     * @return array<mixed>
     */
    private function combineData(string $column, array $array, array $data): array
    {
        $queryColumn = array_map(function ($key) {
            return str_replace($this->combineKeys, '', $key);
        }, $data['query'][$column]);

        return array_combine($queryColumn, $array);
    }
}
