<?php
declare(strict_types=1);

namespace App\Service;

use App\Repository\ProductRepositoryInterface;

readonly class VendingMachine
{
    public function __construct(private ProductRepositoryInterface $productRepository) {

    }

    public function listProducts(): array {
        return $this->productRepository->findAll();
    }
}