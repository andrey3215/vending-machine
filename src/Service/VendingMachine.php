<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Enum\Coin;
use App\Exception\InsufficientAmountException;
use App\Repository\ProductRepositoryInterface;

readonly class VendingMachine
{
    /**
     * @param  ProductRepositoryInterface  $productRepository
     */
    public function __construct(private ProductRepositoryInterface $productRepository)
    {

    }

    /**
     * @return array
     */
    public function listProducts(): array
    {
        return $this->productRepository->findAll();
    }

    /**
     * @return string
     */
    public function getCoinsToString(): string
    {
        return Coin::valuesToString();
    }

    /**
     * @param  string  $coin
     * @return bool
     */
    public function isValidCoin(string $coin): bool
    {
        return (bool)Coin::tryFrom($coin);
    }

    /**
     * @param  Product  $product
     * @param  float    $amount
     * @return float
     * @throws InsufficientAmountException
     */
    public function calculateChange(Product $product, float $amount): float
    {
        $change = $amount - $product->price;
        if ($change < 0) {
            throw new InsufficientAmountException();
        }
        return $change;
    }
}