<?php

namespace App\Exceptions;

use Exception;

class RateLimitExceededException extends Exception
{
    protected $code = 429;
    protected int $retryAfter = 60;
    
    public function __construct(int $retryAfter = 60, ?\Throwable $previous = null)
    {
        parent::__construct("Rate limit exceeded. Retry after {$retryAfter} seconds", 429, $previous);
        $this->retryAfter = $retryAfter;
    }
    
    public function getRetryAfter(): int
    {
        return $this->retryAfter;
    }
}