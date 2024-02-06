<?php

namespace Hywax\YaMetrika;

use DateInterval;
use DateTime;

class Utils
{
    /**
     * Get difference date
     *
     * @param int $days
     * @return array<DateTime>
     */
    public static function getDifferenceDate(int $days): array
    {
        $endDate = new DateTime();
        $startDate = (clone $endDate)->sub(new DateInterval("P{$days}D"));

        return [$startDate, $endDate];
    }
}
