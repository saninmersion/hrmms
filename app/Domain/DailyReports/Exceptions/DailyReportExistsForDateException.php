<?php


namespace App\Domain\DailyReports\Exceptions;


class DailyReportExistsForDateException extends \Exception
{
    const MESSAGE = 'Report already exists for date';

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
