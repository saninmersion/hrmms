<?php

namespace App\Infrastructure\Support\Exceptions;

use Exception;

/**
 * Class InvalidInertiaDataSharableClassException
 * @package App\Infrastructure\Support\Exceptions
 */
class InvalidInertiaDataSharableClassException extends Exception
{
    private const MESSAGE = "Data sharing class should implement DataSharableInterface contract";

    /**
     * InvalidDataSharedClassException constructor.
     *
     * @param string $message
     */
    public function __construct($message = "")
    {
        parent::__construct($message ?: self::MESSAGE);
    }
}
