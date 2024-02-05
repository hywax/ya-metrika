<?php

namespace Hywax\YaMetrika;

class Utils
{
    public static function getDifferenceDate(int $days): array
    {
        $endDate = new \DateTime();
        $startDate = (clone $endDate)->sub(new \DateInterval("P{$days}D"));

        return [$startDate, $endDate];
    }
}
