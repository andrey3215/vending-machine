<?php
declare(strict_types=1);

namespace App\Storage;

interface StorageDriverInterface
{
    public function fetchAll(): array;
}