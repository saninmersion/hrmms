<?php


namespace App\Application\Api\Requests;


use App\Infrastructure\Support\Requests\ApiRequest;

class DailyReportsBatchCreateRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            '*.total_surveyed' => 'required',
            '*.report_date'    => 'required',
            '*.enumerator_id'  => 'required',
        ];
    }
}
