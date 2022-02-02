<?php

namespace App\Application\Api\Requests;

use App\Infrastructure\Support\Requests\ApiRequest;

class DailyReportUpdateRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
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
            'enumerator_id'  => 'गणक',
            'total_surveyed' => 'कुल सर्वेक्षण',
        ];
    }
}
