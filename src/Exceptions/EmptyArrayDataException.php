<?php

declare(strict_types=1);

namespace Matmper\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Throwable;

class EmptyArrayDataException extends Exception
{
/**
    * @param string|null $message
    * @param int $code
    * @param Throwable|null $previous
    */
    public function __construct(
        string $message = null,
        int $code = 0,
        Throwable $previous = null,
    ) {
        $message = "Base Repository: Array `{$message}` cannot be empty";
        $code = $code ? $code : Response::HTTP_NOT_ACCEPTABLE;

        parent::__construct($message, $code, $previous);
    }
}
