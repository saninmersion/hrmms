<?php


namespace App\Application\Api\Requests;


use App\Infrastructure\Support\Requests\ApiRequest;

class DailyReportsBatchUpdateRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            '*.id'             => 'required',
            '*.total_surveyed' => 'required',
            '*.report_date'    => 'required',
            '*.enumerator_id'  => 'required',
        ];
    }
}
