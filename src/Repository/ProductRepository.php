<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Storage\StorageDriverInterface;

final readonly class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @param  StorageDriverInterface  $storageDriver
     */
    public function __construct(private StorageDriverInterface $storageDriver)
    {

    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $data = $this->storageDriver->fetchAll();

        return array_map(function ($item) {
            return new Product($item['name'], $item['price']);
        }, $data);
    }
}