<?php

namespace App\Infrastructure\Support\BikramSambat;

/**
 * Class InvalidDateException
 * @package App\Infrastructure\Support\BikramSambat
 */
class InvalidDateException extends \Exception
{
    public const MESSAGE = 'Invalid date passed.';

    /**
     * InvalidDateException constructor.
     *
     * @param string $message
     */
    public function __construct(string $message = "")
    {
        parent::__construct($message ?: self::MESSAGE);
    }
}
