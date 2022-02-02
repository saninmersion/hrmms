<?php


namespace App\Application\Api\Controllers;


use App\Application\Api\Requests\DailyReportsBatchCreateRequest;
use App\Application\Api\Requests\DailyReportsBatchUpdateRequest;
use App\Domain\DailyReports\Exceptions\DailyReportExistsForDateException;
use App\Domain\DailyReports\Presenters\DailyReportApiPresenter;
use App\Domain\DailyReports\Repositories\DailyReportRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\ApiController;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DailyReportsBatchController extends ApiController
{
    /**
     * @var \App\Domain\DailyReports\Repositories\DailyReportRepository
     */
    private DailyReportRepository $dailyReportRepository;
    /**
     * @var \Illuminate\Database\DatabaseManager
     */
    private DatabaseManager $databaseManager;

    public function __construct(DailyReportRepository $dailyReportRepository, DatabaseManager $databaseManager)
    {
        $this->dailyReportRepository = $dailyReportRepository;
        $this->databaseManager       = $databaseManager;
    }

    /**
     * @param \App\Application\Api\Requests\DailyReportsBatchCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DailyReportsBatchCreateRequest $request)
    {
        $reports = collect($request->validated());

        /** @var User $user */
        $user = AuthHelper::currentUser(Guard::API);

        $this->dailyReportRepository->setPresenter(DailyReportApiPresenter::class);

        $dailyReports = [];

        $this->databaseManager->beginTransaction();

        try {
            $reports->each(
                function ($dailyReport) use ($user, &$dailyReports) {
                    $checkReport = $this->dailyReportRepository->byEnumerator($dailyReport['enumerator_id'])->byReportDateAndCreatedBy($dailyReport['report_date'], $user->id)->all();

                    if ( $checkReport ) {
                        throw new DailyReportExistsForDateException();
                    }
                    $dailyReports[] = $this->dailyReportRepository->create(
                        [
                            'created_by'       => $user->id,
                            'total_surveyed'   => $dailyReport['total_surveyed'],
                            'report_date'      => $dailyReport['report_date'],
                            'district_code'    => $user->district_code,
                            'census_office_id' => $user->census_office_id,
                            'enumerator_id'    => $dailyReport['enumerator_id'],
                        ]
                    );

                }
            );

            $grouped = collect($dailyReports)->groupBy(
                function ($report) {
                    return Arr::get($report, 'report_date.date');
                }
            )->map(
                function ($item, $key) use ($user) {
                    $enumerators = collect($item)->map(
                        function ($report) {
                            return [
                                'id'              => Arr::get($report, 'id'),
                                'total_surveyed'  => Arr::get($report, 'total_surveyed'),
                                'enumerator_id'   => Arr::get($report, 'enumerator_id'),
                                'enumerator_name' => Arr::get($report, 'enumerator_name'),
                            ];
                        }
                    );

                    return [
                        'id'          => $key,
                        "created_by"  => $user->id,
                        'enumerators' => $enumerators,
                        'report_date' => Arr::get($item, '0.report_date'),
                    ];
                }
            )->values()->toArray();

            $this->databaseManager->commit();

            return $this->sendResponse(
                [
                    'reports' => $grouped,
                ],
                'Reports Created'
            );
        } catch ( DailyReportExistsForDateException $exception ) {
            $this->databaseManager->rollBack();

            return $this->sendError("Report already exists for provided date", Response::HTTP_BAD_REQUEST);
        } catch ( Exception $exception ) {
            $this->databaseManager->rollBack();

            return $this->sendError("Error creating report");
        }
    }

    /**
     * @param \App\Application\Api\Requests\DailyReportsBatchUpdateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(DailyReportsBatchUpdateRequest $request)
    {
        $reports = collect($request->validated());

        $this->dailyReportRepository->setPresenter(DailyReportApiPresenter::class);

        $dailyReports = [];

        $this->databaseManager->beginTransaction();

        try {
            $reports->each(
                function ($item) use (&$dailyReports) {
                    $dailyReports[] = $this->dailyReportRepository->update(
                        [
                            'report_date'    => Arr::get($item, 'report_date'),
                            'total_surveyed' => Arr::get($item, 'total_surveyed'),
                        ],
                        Arr::get($item, 'id')
                    );
                }
            );

            $grouped = collect($dailyReports)->groupBy(
                function ($report) {
                    return Arr::get($report, 'report_date.date');
                }
            )->map(
                function ($item, $key) {
                    $enumerators = collect($item)->map(
                        function ($report) {
                            return [
                                'id'              => Arr::get($report, 'id'),
                                'total_surveyed'  => Arr::get($report, 'total_surveyed'),
                                'enumerator_id'   => Arr::get($report, 'enumerator_id'),
                                'enumerator_name' => Arr::get($report, 'enumerator_name'),
                            ];
                        }
                    );

                    return [
                        'id'          => $key,
                        "created_by"  => Arr::get($item, '0.created_by'),
                        'enumerators' => $enumerators,
                        'report_date' => Arr::get($item, '0.report_date'),
                    ];
                }
            )->values()->toArray();

            $this->databaseManager->commit();

            return $this->sendResponse(
                [
                    'reports' => $grouped,
                ],
                'Report Updated'
            );
        } catch ( Exception $exception ) {
            return $this->sendError("Error updating report");
        }
    }
}
