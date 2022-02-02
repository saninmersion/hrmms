<?php

namespace App\Application\Api\Requests;

use App\Infrastructure\Support\Requests\ApiRequest;

/**
 * Class DailyReportCreateRequest
 *
 * @package App\Application\Api\Requests
 */
class DailyReportCreateRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'report_date'    => 'required',
            'enumerator_id'  => 'required',
            'total_surveyed' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributes()
    {
        return [
            'report_date'    => 'रिपोर्ट मिति',
            'enumerator_id'  => 'गणक',
            'total_surveyed' => 'कुल सर्वेक्षण',
        ];
    }
}
