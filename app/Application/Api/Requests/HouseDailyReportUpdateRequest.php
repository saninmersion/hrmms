<?php

namespace App\Application\Api\Requests;

use App\Infrastructure\Support\Requests\ApiRequest;

class HouseDailyReportUpdateRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'total_surveyed'               => 'required',
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
