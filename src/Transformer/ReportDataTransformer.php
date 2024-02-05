<?php

namespace Hywax\YaMetrika\Transformer;

use Hywax\YaMetrika\Exception\ReportTransformerException;
use Hywax\YaMetrika\Interface\Transformer;

class ReportDataTransformer implements Transformer
{
    private array $combineKeys = [
        'ym:s:', 'ym:pv:', 'ym:ad:', 'ym:sp:'
    ];

    public function getCombineKeys(): array
    {
        return $this->combineKeys;
    }

    public function setCombineKeys(array $combineKeys): void
    {
        $this->combineKeys = $combineKeys;
    }

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

    private function combineData(string $column, array $array, array $data): array
    {
        $queryColumn = array_map(function ($key) {
            return str_replace($this->combineKeys, '', $key);
        }, $data['query'][$column]);

        return array_combine($queryColumn, $array);
    }

    public static function call(array $data): array
    {
        return (new self($data))->transform();
    }
}
