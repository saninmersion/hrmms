<?php

namespace App\Application\Api\Requests;

use App\Infrastructure\Support\Requests\ApiRequest;

/**
 * Class HouseDailyReportCreateRequest
 *
 * @package App\Application\Api\Requests
 */
class HouseDailyReportCreateRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'report_date'                  => 'required',
            'total_surveyed'               => ['required', 'integer', 'min:1'],
            'number_of_houses_in_census'   => ['required', 'integer', 'min:1'],
            'number_of_families_in_census' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributes()
    {
        return [
            'report_date'                  => 'रिपोर्ट मिति',
            'total_surveyed'               => 'घर संख्या',
            'number_of_families_in_census' => 'जनगणना घर संख्या',
            'number_of_houses_in_census'   => 'जनगणना परिवार संख्या',
        ];
    }
}
