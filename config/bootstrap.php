<?php

require __DIR__.'/../vendor/autoload.php';

use App\Command\VendingMachineCommand;
use App\Storage\JsonStorageDriver;
use App\Repository\ProductRepository;
use App\Service\VendingMachine;
use Symfony\Component\Console\Application;

$config = require __DIR__ . '/config.php';
$jsonFilePath = $config['storage']['json']['path'];
$storageDriver = new JsonStorageDriver($jsonFilePath);
$productRepository = new ProductRepository($storageDriver);
$vendingMachine = new VendingMachine($productRepository);

$application = new Application();
$application->add(new VendingMachineCommand($vendingMachine));

return $application;
