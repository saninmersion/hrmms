<?php

namespace App\Infrastructure\Support\Exceptions;

use Exception;

/**
 * Class FileNotFoundException
 * @package App\Infrastructure\Support\Exceptions
 */
class FileNotFoundException extends Exception
{
    private const MESSAGE = "The file you are looking for was not found";

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
