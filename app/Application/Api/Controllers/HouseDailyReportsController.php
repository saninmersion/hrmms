<?php

namespace App\Application\Api\Controllers;

use App\Application\Api\Requests\HouseDailyReportCreateRequest;
use App\Application\Api\Requests\HouseDailyReportUpdateRequest;
use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\HouseDailyReports\Exceptions\HouseDailyReportExistsForDateException;
use App\Domain\HouseDailyReports\Presenters\HouseDailyReportApiPresenter;
use App\Domain\HouseDailyReports\Repositories\HouseDailyReportRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\ApiController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Class HouseDailyReportsController
 *
 * @package App\Application\Api\Controllers
 */
class HouseDailyReportsController extends ApiController
{
    /**
     * @var HouseDailyReportRepository
     */
    protected HouseDailyReportRepository $dailyReportRepository;
    /**
     * @var DistrictRepository
     */
    protected DistrictRepository $districtRepository;

    /**
     * HouseDailyReportsController constructor.
     *
     * @param HouseDailyReportRepository $dailyReportRepository
     */
    public function __construct(HouseDailyReportRepository $dailyReportRepository, DistrictRepository $districtRepository)
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

        $this->dailyReportRepository->setPresenter(HouseDailyReportApiPresenter::class);

        try {
            $reports = $this->dailyReportRepository->byCreatedBy($user->id)->all();

            return $this->sendResponse(
                [
                    'reports' => $reports,
                ],
                'Report List Of User'
            );
        } catch (Exception $exception) {

            return $this->sendError("Error fetching report list");
        }
    }

    /**
     * @param HouseDailyReportCreateRequest $request
     *
     * @return JsonResponse
     */
    public function store(HouseDailyReportCreateRequest $request)
    {
        /** @var User $user */
        $user = AuthHelper::currentUser(Guard::API);

        $this->dailyReportRepository->setPresenter(HouseDailyReportApiPresenter::class);

        try {
            $checkReport = $this->dailyReportRepository->byReportDateAndCreatedBy($request->get('report_date'), $user->id)->all();

            if ( $checkReport ) {
                throw new HouseDailyReportExistsForDateException();
            }
            $report = $this->dailyReportRepository->create(
                [
                    'created_by'                   => $user->id,
                    'total_surveyed'               => $request->get('total_surveyed'),
                    'number_of_houses_in_census'   => $request->get('number_of_houses_in_census'),
                    'number_of_families_in_census' => $request->get('number_of_families_in_census'),
                    'report_date'                  => $request->get('report_date'),
                    'district_code'                => $user->district_code,
                    'census_office_id'             => $user->census_office_id,
                ]
            );

            return $this->sendResponse(
                [
                    'report' => $report,
                ],
                'Report Created'
            );
        } catch (HouseDailyReportExistsForDateException $exception) {
            return $this->sendError("Report already exists for provided date", Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            return $this->sendError("Error creating report");
        }
    }

    /**
     * @param HouseDailyReportUpdateRequest $request
     * @param int                           $reportId
     *
     * @return JsonResponse
     */
    public function update(HouseDailyReportUpdateRequest $request, $reportId)
    {
        $this->dailyReportRepository->setPresenter(HouseDailyReportApiPresenter::class);
        try {
            $report = $this->dailyReportRepository->update($request->validated(), $reportId);

            return $this->sendResponse(
                [
                    'report' => $report,
                ],
                'Report Updated'
            );
        } catch (Exception $exception) {
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
        } catch (Exception $exception) {
            return $this->sendError("Error deleting report");
        }
    }
}
