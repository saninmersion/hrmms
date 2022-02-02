<?php

namespace App\Infrastructure\Support\Unicode;

/**
 * Class UnicodeNumberException
 * @package App\Infrastructure\Support\Unicode
 */
class UnicodeNumberException extends \Exception
{
    public const MESSAGE = 'An error occurred';

    /**
     * UnicodeNumberException constructor.
     *
     * @param string $message
     */
    public function __construct(string $message = '')
    {
        parent::__construct($message ?: self::MESSAGE);
    }
}
