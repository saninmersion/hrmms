<?php

namespace App\Application\Front\Support\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

/**
 * Class FormNonEditableException
 * @package App\Application\Front\Support\Exceptions
 */
class FormNonEditableException extends Exception
{
    public const MESSAGE = 'Form already submitted.';

    /**
     * FormNonEditableException constructor.
     *
     * @param string $message
     */
    public function __construct($message = "")
    {
        parent::__construct($message ?: self::MESSAGE);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @return RedirectResponse
     */
    public function render()
    {
        return redirect()->route('front.application.form.preview');
    }

    /**
     * Report the exception.
     *
     * @return bool|void
     */
    public function report()
    {
        return false;
    }
}
