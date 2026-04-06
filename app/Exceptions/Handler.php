<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
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

    // ✅ Render JSON responses for API
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {

            // Validation errors
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $exception->errors(),
                ], 422);
            }

            // Model not found
            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Resource not found',
                ], 404);
            }

            // Route not found
            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Endpoint not found',
                ], 404);
            }

            // Authentication exception
            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            // General exception
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
