<?php

namespace App\Domain\Applicants\Support\Exceptions;

use Illuminate\Http\Response;

/**
 * Class LoginFailedException
 * @package App\Domain\Applicants\Support\Exceptions
 */
class LoginFailedException extends \Exception
{
    /**
     * LoginFailedException constructor.
     *
     * @param string $reason
     */
    public function __construct(string $reason)
    {
        parent::__construct($reason, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->message;
    }
}
