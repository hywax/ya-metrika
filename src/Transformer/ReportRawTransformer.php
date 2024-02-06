<?php

namespace Hywax\YaMetrika\Transformer;

use Hywax\YaMetrika\Interface\Transformer;

class ReportRawTransformer implements Transformer
{
    /**
     * Transform the data
     *
     * @param mixed $data
     * @return array<mixed>
     */
    public function transform($data): array
    {
        return $data;
    }
}
