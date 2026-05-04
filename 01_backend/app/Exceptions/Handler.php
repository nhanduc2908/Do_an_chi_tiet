<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof UnauthorizedException) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        if ($e instanceof ForbiddenException) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        
        if ($e instanceof NotFoundException) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        
        if ($e instanceof ValidationException) {
            return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        }
        
        if ($e instanceof TooManyRequestsException || $e instanceof RateLimitExceededException) {
            return response()->json(['message' => 'Too Many Requests'], 429);
        }
        
        if ($e instanceof ConflictException) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
        
        if ($e instanceof ServiceUnavailableException) {
            return response()->json(['message' => 'Service Unavailable'], 503);
        }
        
        if ($e instanceof DomainException || $e instanceof ApplicationException) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
        
        return parent::render($request, $e);
    }
}