<?php

namespace App\Application\Api\Controllers;

use App\Application\Api\Requests\DailyReportCreateRequest;
use App\Application\Api\Requests\DailyReportUpdateRequest;
use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\DailyReports\Exceptions\DailyReportExistsForDateException;
use App\Domain\DailyReports\Presenters\DailyReportApiPresenter;
use App\Domain\DailyReports\Repositories\DailyReportRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\ApiController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

/**
 * Class DailyReportsController
 * @package App\Application\Api\Controllers
 */
class DailyReportsController extends ApiController
{
    /**
     * @var DailyReportRepository
     */
    protected DailyReportRepository $dailyReportRepository;
    /**
     * @var DistrictRepository
     */
    protected DistrictRepository $districtRepository;

    /**
     * DailyReportsController constructor.
     *
     * @param DailyReportRepository $dailyReportRepository
     */
    public function __construct(DailyReportRepository $dailyReportRepository, DistrictRepository $districtRepository)
    {
        $this->dailyReportRepository = $dailyReportRepository;
        $this->districtRepository    = $districtRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        /** @var User $user */
        $user = AuthHelper::currentUser(Guard::API);

        $this->dailyReportRepository->setPresenter(DailyReportApiPresenter::class);

        try {
            $reports = collect($this->dailyReportRepository->byCreatedBy($user->id)->all());

            $grouped = $reports->groupBy(
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

            return $this->sendResponse(
                [
                    'reports' => $grouped,
                ],
                'List of enumerator daily report'
            );
        } catch ( Exception $exception ) {

            return $this->sendError("Error fetching report list");
        }
    }

    /**
     * @param DailyReportCreateRequest $request
     *
     * @return JsonResponse
     */
    public function store(DailyReportCreateRequest $request)
    {
        /** @var User $user */
        $user = AuthHelper::currentUser(Guard::API);

        $this->dailyReportRepository->setPresenter(DailyReportApiPresenter::class);

        try {
            $checkReport = $this->dailyReportRepository->byEnumerator($request->input('enumerator_id'))->byReportDateAndCreatedBy($request->get('report_date'), $user->id)->all();

            if ( $checkReport ) {
                throw new DailyReportExistsForDateException();
            }
            $report = $this->dailyReportRepository->create(
                [
                    'created_by'       => $user->id,
                    'total_surveyed'   => $request->get('total_surveyed'),
                    'report_date'      => $request->get('report_date'),
                    'district_code'    => $user->district_code,
                    'census_office_id' => $user->census_office_id,
                    'enumerator_id'    => $request->input('enumerator_id'),
                ]
            );

            return $this->sendResponse(
                [
                    'report' => $report,
                ],
                'Report Created'
            );
        } catch ( DailyReportExistsForDateException $exception ) {
            return $this->sendError("Report already exists for provided date", Response::HTTP_BAD_REQUEST);
        } catch ( Exception $exception ) {
            return $this->sendError("Error creating report");
        }
    }

    /**
     * @param DailyReportUpdateRequest $request
     * @param int                      $reportId
     *
     * @return JsonResponse
     */
    public function update(DailyReportUpdateRequest $request, $reportId)
    {
        $this->dailyReportRepository->setPresenter(DailyReportApiPresenter::class);
        try {
            $report = $this->dailyReportRepository->update($request->validated(), $reportId);

            return $this->sendResponse(
                [
                    'report' => $report,
                ],
                'Report Updated'
            );
        } catch ( Exception $exception ) {
            return $this->sendError("Error updating report");
        }
    }

    /**
     * @param int $reportId
     *
     * @return JsonResponse
     */
    public function destroy($reportId)
    {
        try {
            $this->dailyReportRepository->delete($reportId);

            return $this->sendResponse(
                [],
                'Report Deleted'
            );
        } catch ( Exception $exception ) {
            return $this->sendError("Error deleting report");
        }
    }
}
