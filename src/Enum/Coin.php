<?php
declare(strict_types=1);

namespace App\Enum;

enum Coin: string
{
    case PENNY = '0.01';
    case NICKEL = '0.05';
    case DIME = '0.10';
    case QUARTER = '0.25';
    case HALF_DOLLAR = '0.50';
    case DOLLAR = '1.00';

    /**
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn($enum) => number_format((float)$enum->value, 2), self::cases());
    }

    /**
     * @return string
     */
    public static function valuesToString(): string
    {
        return implode(', ', self::values());
    }
}
