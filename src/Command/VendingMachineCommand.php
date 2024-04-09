<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\Product;
use App\Exception\InsufficientAmountException;
use App\Service\VendingMachine;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name       : 'app:vend',
    description: 'Starts the vending machine.',
)]
final class VendingMachineCommand extends Command
{
    /**
     * @param  VendingMachine  $vendingMachine
     * @param  string|null     $name
     */
    public function __construct(private readonly VendingMachine $vendingMachine, ?string $name = null)
    {
        parent::__construct($name);
    }

    /**
     * @param  InputInterface   $input
     * @param  OutputInterface  $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Welcome to the Vending Machine!");

        $products = $this->vendingMachine->listProducts();
        $selectedProduct = $this->selectProduct($input, $output, $products);
        $totalInserted = $this->acceptCoins($input, $output, $selectedProduct);

        $output->writeln("Collect your product: {$selectedProduct->name}");
        try {
            $this->returnChange($output, $totalInserted, $selectedProduct);
        }catch (InsufficientAmountException $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * @param  InputInterface   $input
     * @param  OutputInterface  $output
     * @param  array            $products
     * @return Product
     */
    private function selectProduct(InputInterface $input, OutputInterface $output, array $products): Product
    {
        $productNames = array_map(function (Product $product) {
            return "{$product->name} \${$product->price}";
        }, $products);

        $question = new ChoiceQuestion(
            'Please select a product:',
            $productNames
        );
        $question->setErrorMessage('Product %s is invalid.');

        $productName = $this->getHelper('question')->ask($input, $output, $question);
        $output->writeln("You selected: {$productName}");

        $selectedProductIndex = array_search($productName, $productNames);

        return $products[$selectedProductIndex];
    }

    /**
     * @param  InputInterface   $input
     * @param  OutputInterface  $output
     * @param  Product          $selectedProduct
     * @return float
     */
    private function acceptCoins(InputInterface $input, OutputInterface $output, Product $selectedProduct): float
    {
        $totalInserted = 0;
        $supportedCoinString = $this->vendingMachine->getCoinsToString();
        while ($totalInserted < $selectedProduct->price) {
            $question = new Question("Please insert coins ({$supportedCoinString}): ");
            $coin = $this->getHelper('question')->ask($input, $output, $question);
            if ($this->vendingMachine->isValidCoin($coin)) {
                $totalInserted += $coin;
                $output->writeln("Total inserted: \$$totalInserted");
            } else {
                $output->writeln("Invalid coin.");
            }
        }

        return $totalInserted;
    }

    /**
     * @param  OutputInterface  $output
     * @param  float            $totalInserted
     * @param  Product          $selectedProduct
     * @return void
     * @throws InsufficientAmountException
     */
    private function returnChange(OutputInterface $output, float $totalInserted, Product $selectedProduct): void
    {
        $change = $this->vendingMachine->calculateChange($selectedProduct, $totalInserted);
        if ($change > 0) {
            $output->writeln("Collect your change: \${$change}");
        }
    }
}