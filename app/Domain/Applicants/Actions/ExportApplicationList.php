<?php

namespace App\Domain\Applicants\Actions;

use App\Domain\Applicants\Exports\ApplicationListExport;
use App\Domain\Applicants\Models\ApplicantExport;
use App\Domain\Applicants\Repositories\ApplicantExportRepository;
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
 * Class ExportApplicationList
 *
 * @package App\Domain\Applicants\Actions
 */
class ExportApplicationList
{
    /**
     * @var ApplicantExportRepository
     */
    protected ApplicantExportRepository $exportRepository;
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
     * @param ApplicantExportRepository $exportRepository
     * @param LastUpdatedRepository     $lastUpdatedRepository
     * @param ZipService                $zipService
     */
    public function __construct(ApplicantExportRepository $exportRepository, LastUpdatedRepository $lastUpdatedRepository, ZipService $zipService)
    {
        $this->exportRepository      = $exportRepository;
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

        /** @var LastUpdated $lastUpdatedAt */
        $lastUpdatedAt = $this->lastUpdatedRepository->getByName(UpdateDataTypes::APPLICATION_LIST);
        $lastUpdatedAt = isset($lastUpdatedAt->updated_at) ? $lastUpdatedAt->updated_at : now();

        $filename    = sprintf("applications-%s.csv", $lastUpdatedAt->format('Y-m-d-H-i-s'));
        $zipFilename = sprintf("applications-%s.zip", $lastUpdatedAt->format('Y-m-d-H-i-s'));
        $tempPath    = storage_path("exports/{$filename}");
        $path        = sprintf("%s/%s", General::PATH_EXPORTS, $zipFilename);

        /** @var ApplicantExport $applicantExport */
        $applicantExport = $this->exportRepository->create(
            [
                'filename' => $zipFilename,
                'path'     => $path,
                'metadata' => [
                    'data_as_of' => $lastUpdatedAt->toDateTimeString(),
                ],
            ]
        );

        try {
            (new ApplicationListExport())->export($tempPath);

            $status = true;
        } catch (Exception $exception) {
            $status = false;
            Helper::logException($exception);
        }

        $metadata           = (array) $applicantExport->metadata;
        $metadata['status'] = $status;
        $applicantExport->update(
            [
                'exported_at' => now(),
                'metadata'    => $metadata,
            ]
        );

        $zipPath = Storage::disk('local')->path($zipFilename);
        $this->zipService->convertToZip([$filename => $tempPath], $zipPath);
        /** @var resource $inputStream */
        $inputStream = Storage::disk('local')->getDriver()->readStream($zipFilename);

        Storage::disk('minio')->put($path, $inputStream);
        unlink($tempPath);
        unlink($zipPath);
    }
}
