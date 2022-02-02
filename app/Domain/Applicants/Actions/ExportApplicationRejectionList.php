<?php

namespace App\Domain\Applicants\Actions;

use App\Domain\Applicants\Exports\ApplicationRejectionListExport;
use App\Domain\Applicants\Models\ApplicantExport;
use App\Domain\LastUpdated\Models\LastUpdated;
use App\Domain\LastUpdated\Repositories\LastUpdatedRepository;
use App\Infrastructure\Constants\General;
use App\Infrastructure\Constants\UpdateDataTypes;
use App\Infrastructure\Support\Helper;
use App\Infrastructure\Support\Zip\ZipService;
use Exception;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;

/**
 * Class ExportApplicationRejectionList
 * @package App\Domain\Applicants\Actions
 */
class ExportApplicationRejectionList
{
    /**
     * @var LastUpdatedRepository
     */
    protected LastUpdatedRepository $lastUpdatedRepository;
    /**
     * @var ZipService
     */
    protected ZipService $zipService;

    /**
     * ExportApplicationList constructor.
     *
     * @param LastUpdatedRepository $lastUpdatedRepository
     * @param ZipService            $zipService
     */
    public function __construct(LastUpdatedRepository $lastUpdatedRepository, ZipService $zipService)
    {
        $this->lastUpdatedRepository = $lastUpdatedRepository;
        $this->zipService            = $zipService;
    }

    /**
     * Export application list
     *
     * 1. Get Last updated date
     * 2. Filename with last updated date
     * 3. Store export database
     * 3. Export file with filename
     * 4. Update export database
     * 5. Move exported file to cloud
     * 6. Delete exported file from local
     *
     * @throws FileNotFoundException
     */
    public function export(): void
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '1000');

        $filename    = sprintf("recommended_for_rejection_applications.csv");
        $zipFilename = sprintf("recommended_for_rejection_applications.zip");
        $tempPath    = storage_path("exports/{$filename}");
        $path        = sprintf("%s/%s", General::PATH_EXPORTS, $zipFilename);

        try {
            (new ApplicationRejectionListExport())->export($tempPath);
        } catch (Exception $exception) {
            Helper::logException($exception);
        }

        $zipPath = Storage::disk('local')->path($zipFilename);
        $this->zipService->convertToZip([$filename => $tempPath], $zipPath);

        /** @var resource $inputStream */
        $inputStream = Storage::disk('local')->getDriver()->readStream($zipFilename);

        Storage::disk('minio')->put($path, $inputStream);
        unlink($tempPath);
        unlink($zipPath);
    }
}
