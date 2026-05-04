<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected $code = 422;
    protected array $errors = [];
    
    public function __construct(string $message = "Validation failed", array $errors = [], ?\Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
        $this->errors = $errors;
    }
    
    public function errors(): array
    {
        return $this->errors;
    }
}