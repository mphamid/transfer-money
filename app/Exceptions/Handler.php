<?php

namespace App\Exceptions;

use App\Base\ServiceException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ServiceException) {
            $statusCode = $e->getCode() === 0 ? 400 : $e->getCode();
            return response()->json(['message' => $e->getResponseMessage()], $statusCode);
        }
        return parent::render($request, $e);
    }
}
