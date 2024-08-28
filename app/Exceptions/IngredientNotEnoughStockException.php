<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class IngredientNotEnoughStockException extends HttpException
{
    public function __construct($message = null, Throwable $previous = null, array $headers = [], $code = 0)
    {
        parent::__construct(400, $message, $previous, $headers, $code);
    }
}
