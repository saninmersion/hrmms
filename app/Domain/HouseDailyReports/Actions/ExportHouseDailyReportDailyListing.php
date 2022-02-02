<?php

namespace App\Domain\HouseDailyReports\Actions;

use App\Application\Admin\Notifications\ExportCompletedNotification;
use App\Application\Admin\Notifications\ExportStartedNotification;
use App\Domain\HouseDailyReports\Exports\HouseDailyReportDailyListingExport;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\General;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Helper;
use App\Infrastructure\Support\Zip\ZipService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;
use Exception;

/**
 * Class ExportHouseDailyReportDailyListing
 * @package App\Domain\HouseDailyReports\Actions
 */
class ExportHouseDailyReportDailyListing implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var User|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $user;

    /**
     * ExportHouseDailyReportDailyListing constructor.
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * Export House Daily Report list
     *
     * 1. Get Last updated date
     * 2. Filename with last updated date
     * 3. Store export database
     * 3. Export file with filename
     * 4. Update export database
     * 5. Move exported file to cloud
     * 6. Delete exported file from local
     *
     * @param ZipService $zipService
     *
     * @throws FileNotFoundException
     */
    public function handle(ZipService $zipService): void
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '1000');

        $filename    = sprintf("daily_house_listing_progress_%s.csv", now());
        $zipFilename = sprintf("daily_house_listing_progress_%s.zip", now());
        $tempPath    = storage_path("exports/{$filename}");
        $path        = sprintf("%s/%s", General::PATH_EXPORTS, $zipFilename);

        $exportDetails = [
            'type'     => 'Daily House Listing Progress',
            'filename' => $zipFilename,
        ];

        try {
            (new HouseDailyReportDailyListingExport())->export($tempPath);
        } catch (Exception $exception) {
            Helper::logException($exception);

            return;
        }

        $zipPath = Storage::disk('local')->path($zipFilename);

        $zipService->convertToZip([$filename => $tempPath], $zipPath);

        /** @var resource $inputStream */
        $inputStream = Storage::disk('local')->getDriver()->readStream($zipFilename);

        Storage::disk('minio')->put($path, $inputStream);
        Notification::send([$this->user], new ExportCompletedNotification($this->user, Helper::fileUrl($path), $exportDetails));
        unlink($tempPath);
        unlink($zipPath);
    }
}
