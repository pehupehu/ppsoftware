<?php

namespace App\Tools;

/**
 * Class Currency
 * @package App\Tools
 */
class Currency
{
    const EUR = 'EUR';

    public static function getSupportedCurrency()
    {
        return [
            self::EUR => self::EUR,
        ];
    }
}
