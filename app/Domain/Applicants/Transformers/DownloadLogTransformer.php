<?php

namespace App\Domain\Applicants\Transformers;

use App\Domain\Applicants\Models\DownloadLog;
use App\Domain\Users\Transformers\AuthUserTransformer;
use App\Infrastructure\Support\Helper;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

/**
 * Class DownloadLogTransformer
 * @package App\Domain\Applicants\Transformers
 */
class DownloadLogTransformer extends TransformerAbstract
{
    /**
     * @var string[]
     */
    protected $defaultIncludes = ['downloaded_by', 'exported_file'];

    /**
     * @param DownloadLog $log
     *
     * @return array
     */
    public function transform(DownloadLog $log): array
    {
        return [
            'id'            => $log->id,
            'downloaded_at' => Helper::dateResponse($log->created_at),
        ];
    }

    /**
     * @param DownloadLog $log
     *
     * @return Item
     */
    public function includeDownloadedBy(DownloadLog $log): Item
    {
        return $this->item($log->downloaded_by, new AuthUserTransformer());
    }

    /**
     * @param DownloadLog $log
     *
     * @return Item
     */
    public function includeExportedFile(DownloadLog $log): Item
    {
        return $this->item($log->exported_file, new ApplicantExportListTransformer());
    }
}
