<?php

namespace App\Infrastructure\Support\Exceptions;

use App\Application\Front\Support\Exceptions\FormNonEditableException;
use Illuminate\Foundation\Exceptions\Handler as BaseHandler;
use Throwable;

/**
 * Class Handler
 * @package App\Infrastructure\Support\Exceptions
 */
class Handler extends BaseHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        FormNonEditableException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     * @SuppressWarnings("unused")
     */
    public function register()
    {
        $this->reportable(
            function (Throwable $e) {
                //
            }
        );
    }

    /**
     * @param Throwable $exception
     *
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        if ( $this->shouldReport($exception) && app()->bound('sentry') ) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }
}
