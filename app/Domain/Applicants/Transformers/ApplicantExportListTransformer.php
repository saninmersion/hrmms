<?php

namespace App\Domain\Applicants\Transformers;

use App\Domain\Applicants\Models\ApplicantExport;
use App\Infrastructure\Support\Helper;
use League\Fractal\TransformerAbstract;

/**
 * Class ApplicantExportListTransformer
 * @package App\Domain\Applicants\Transformers
 */
class ApplicantExportListTransformer extends TransformerAbstract
{
    public function transform(ApplicantExport $export): array
    {
        return [
            'id'           => $export->id,
            'filename'     => $export->filename ?? '',
            'path'         => $export->path ?? '',
            'download_url' => $export->url ?? '#',
            'date_as_of'   => Helper::dateResponse($export->parsed_as_of_date),
        ];
    }
}
