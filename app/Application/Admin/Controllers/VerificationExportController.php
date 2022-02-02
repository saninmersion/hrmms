<?php

namespace App\Application\Admin\Controllers;

use App\Domain\Applicants\Repositories\ApplicantVerificationRepository;
use App\Domain\Applicants\Repositories\ApplicationListRepository;
use App\Infrastructure\Constants\General;
use App\Infrastructure\Support\Controller\AdminController;
use App\Infrastructure\Support\Helper;
use Illuminate\Support\Facades\Storage;

/**
 * Class VerificationExportController
 * @package App\Application\Admin\Controllers
 */
class VerificationExportController extends AdminController
{
    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportRejectionList()
    {
        $fileName = "recommended_for_rejection_applications.zip";
        $path     = sprintf("%s/%s", General::PATH_EXPORTS, $fileName);
        $headers  = [
            'Content-Type'        => 'application/zip',
            'Content-Disposition' => 'attachment',
            'filename'            => $fileName,
        ];

        try {
            return Storage::disk(config('filesystems.default'))->download($path, $fileName, $headers);
        } catch (\Exception $exception) {
            Helper::logException($exception, 'debug');

            abort(\Illuminate\Http\Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCorrectionNeededList()
    {
        $fileName = "correction_needed_applications.zip";
        $path     = sprintf("%s/%s", General::PATH_EXPORTS, $fileName);
        $headers  = [
            'Content-Type'        => 'application/zip',
            'Content-Disposition' => 'attachment',
            'filename'            => $fileName,
        ];

        try {
            return Storage::disk(config('filesystems.default'))->download($path, $fileName, $headers);
        } catch (\Exception $exception) {
            Helper::logException($exception, 'debug');

            abort(\Illuminate\Http\Response::HTTP_NOT_FOUND);
        }
    }
}
