<?php

namespace Hywax\YaMetrika\Interface;

interface Transformer
{
    /**
     * Transform the data
     *
     * @param mixed $data
     * @return mixed
     */
    public function transform($data);
}
