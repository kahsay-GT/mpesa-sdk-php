<?php

namespace Kahsaygt\Mpesa\Exceptions;

use Exception;

/**
 * Class ValidationException
 *
 * Thrown when input validation fails in the M-PESA SDK.
 */
class ValidationException extends Exception
{
    /**
     * ValidationException constructor.
     *
     * @param string $message The validation error message
     * @param int $code The error code (default is 0)
     * @param \Throwable|null $previous The previous exception (if any)
     */
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}