<?php
declare(strict_types=1);

namespace App\Exception;

use Exception;

class InsufficientAmountException extends Exception
{
    protected $message = 'Insufficient amount';
}