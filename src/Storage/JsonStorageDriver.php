<?php
declare(strict_types=1);

namespace App\Storage;

final readonly class JsonStorageDriver implements StorageDriverInterface
{
    /**
     * @param  string  $filePath
     */
    public function __construct(private string $filePath)
    {

    }

    /**
     * @return array
     */
    public function fetchAll(): array
    {
        $jsonData = file_get_contents($this->filePath);

        return json_decode($jsonData, true) ?? [];
    }
}