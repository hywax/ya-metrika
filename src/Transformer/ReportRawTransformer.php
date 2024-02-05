<?php

namespace Hywax\YaMetrika\Transformer;

use Hywax\YaMetrika\Interface\Transformer;

class ReportRawTransformer implements Transformer
{
    public function transform($data): array
    {
        return $data;
    }
}
