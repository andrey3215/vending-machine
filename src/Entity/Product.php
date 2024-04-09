<?php
declare(strict_types=1);

namespace App\Entity;

final readonly class Product
{
    public function __construct(public string $name, public float $price)
    {
    }
}
