<?php

namespace App\Application\Admin\Controllers;

use App\Domain\Applicants\Presenters\ApplicantExportListPresenter;
use App\Domain\Applicants\Presenters\DownloadLogPresenter;
use App\Domain\Applicants\Repositories\ApplicantExportRepository;
use App\Domain\Applicants\Repositories\DownloadLogRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\General;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\AdminController;
use App\Infrastructure\Support\Helper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use Inertia\ResponseFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class ApplicantExportController
 * @package App\Application\Admin\Controllers
 */
class ApplicantExportController extends AdminController
{
    /**
     * @var ApplicantExportRepository
     */
    protected ApplicantExportRepository $exportRepository;
    /**
     * @var DownloadLogRepository
     */
    protected DownloadLogRepository $downloadLogRepository;

    /**
     * ApplicantExportController constructor.
     *
     * @param ApplicantExportRepository $exportRepository
     * @param DownloadLogRepository     $downloadLogRepository
     */
    public function __construct(ApplicantExportRepository $exportRepository, DownloadLogRepository $downloadLogRepository)
    {
        $this->exportRepository      = $exportRepository;
        $this->downloadLogRepository = $downloadLogRepository;
    }

    /**
     * @param Request $request
     *
     * @return Response|ResponseFactory
     */
    public function index(Request $request)
    {
        $queries = $request->all();
        $perPage = $request->input('per_page', General::paginateDefault());

        $this->exportRepository->setPresenter(ApplicantExportListPresenter::class);
        try {
            $exportedFile = $this->exportRepository->getLatestExportedFile();
        } catch (ModelNotFoundException $exception) {
            $exportedFile = null;
        }

        $this->downloadLogRepository->setPresenter(DownloadLogPresenter::class);
        $this->downloadLogRepository->with(['downloaded_by', 'exported_file']);
        $downloadLogs = $this->downloadLogRepository->orderBy('created_at', 'desc')->paginate($perPage);
        $meta         = array_pop($downloadLogs);

        return inertia(
            'Applicants/ApplicantExportsList',
            [
                'exportedFile' => $exportedFile,
                'downloadLogs' => $downloadLogs,
                'pagination'   => $meta['pagination'],
                'queries'      => $queries ?: null,
            ]
        );
    }

    /**
     * @param int                   $exportId
     * @param string                $path
     * @param DownloadLogRepository $repository
     *
     * @return StreamedResponse|void
     */
    public function download($exportId, string $path, DownloadLogRepository $repository)
    {
        $pathInfo = pathinfo($path);

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment',
            'filename'            => $pathInfo['basename'],
        ];

        /** @var User $currentUser */
        $currentUser = AuthHelper::currentUser();

        try {
            $repository->create(
                [
                    'user_id'   => $currentUser->id,
                    'export_id' => (int) $exportId,
                ]
            );

            return Storage::disk(config('filesystems.default'))->download($path, $pathInfo['basename'], $headers);
        } catch (\Exception $exception) {
            Helper::logException($exception, 'debug');

            abort(\Illuminate\Http\Response::HTTP_NOT_FOUND);
        }
    }
}
