<?php
declare(strict_types=1);

namespace App\Repository;

interface ProductRepositoryInterface
{
    public function findAll(): array;
}