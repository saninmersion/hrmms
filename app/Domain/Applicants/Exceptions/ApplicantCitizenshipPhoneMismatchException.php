<?php

namespace App\Domain\Applicants\Exceptions;

class ApplicantCitizenshipPhoneMismatchException extends \Exception
{
    const MESSAGE = 'Invalid Data';

    /**
     * InvalidRequestException constructor.
     *
     * @param string $message
     * @param int    $code
     */
    public function __construct(string $message = "", int $code = 0)
    {
        $message = $message ?: self::MESSAGE;

        parent::__construct($message, $code);
    }
}
