<?php

namespace App\Application\Admin\Controllers;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\CensusOffices\Repositories\CensusOfficeRepository;
use App\Domain\Enumerators\Repositories\EnumeratorRepository;
use App\Domain\HouseDailyReports\Criteria\HouseDailyReportListCriteria;
use App\Domain\HouseDailyReports\Presenters\HouseDailyReportListPresenter;
use App\Domain\HouseDailyReports\Repositories\HouseDailyReportRepository;
use App\Domain\HouseDailyReports\Requests\HouseDailyReportRequest;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\General;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\ResponseFactory;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class HouseDailyReportsController
 *
 * @package App\Application\Admin\Controllers
 */
class HouseDailyReportsController extends AdminController
{
    /**
     * @var HouseDailyReportRepository
     */
    protected HouseDailyReportRepository $houseDailyReportRepository;

    /**
     * @var DistrictRepository
     */
    protected DistrictRepository $districtRepository;

    /**
     * @var CensusOfficeRepository
     */
    protected CensusOfficeRepository $censusOfficeRepository;

    /**
     * @var EnumeratorRepository
     */
    private EnumeratorRepository $enumeratorRepository;

    /**
     * HouseDailyReportsController constructor.
     *
     * @param HouseDailyReportRepository $houseDailyReportRepository
     * @param DistrictRepository         $districtRepository
     * @param CensusOfficeRepository     $censusOfficeRepository
     */
    public function __construct(HouseDailyReportRepository $houseDailyReportRepository, DistrictRepository $districtRepository, CensusOfficeRepository $censusOfficeRepository, EnumeratorRepository $enumeratorRepository)
    {
        $this->houseDailyReportRepository = $houseDailyReportRepository;
        $this->districtRepository         = $districtRepository;
        $this->censusOfficeRepository     = $censusOfficeRepository;
        $this->enumeratorRepository       = $enumeratorRepository;
    }

    /**
     * @param Request $request
     *
     * @return \Inertia\Response|ResponseFactory
     * @throws RepositoryException
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user         = AuthHelper::currentUser();
        $queries      = $request->all();
        $perPage      = $request->input('per_page', General::paginateDefault());
        $dailyReports = [];

        $this->houseDailyReportRepository->with(['district', 'censusOffice', 'supervisor'])->setPresenter(HouseDailyReportListPresenter::class);
        $this->houseDailyReportRepository->pushCriteria(new HouseDailyReportListCriteria($queries));

        if ( in_array($user->role, [AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN]) ) {
            $dailyReports = $this->houseDailyReportRepository->paginate($perPage);
        } else {
            $dailyReports = $this->houseDailyReportRepository->byCreatedBy($user->id)->paginate($perPage);
        }

        $meta = array_pop($dailyReports);

        return inertia(
            'HouseDailyReports/List', [
            'reports'    => $dailyReports,
            'pagination' => $meta['pagination'],
            'queries'    => $queries,
            'options'    => [
                'districts'     => $this->districtRepository->allDistrictForOptions(),
                'censusOffices' => $this->censusOfficeRepository->all(),
            ],
            ]
        );
    }

    /**
     * @return \Inertia\Response|ResponseFactory
     */
    public function create()
    {
        return inertia('HouseDailyReports/Create', []);
    }

    /**
     * @param HouseDailyReportRequest $request
     *
     * @return RedirectResponse
     */
    public function store(HouseDailyReportRequest $request)
    {
        $request->setForm()->store();

        session()->flash("success", "House Daily Report Saved");

        return redirect()->route('admin.house-daily-reports.index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param int $reportId
     *
     * @return \Inertia\Response|ResponseFactory
     */
    public function edit($reportId)
    {
        $this->houseDailyReportRepository->setPresenter(HouseDailyReportListPresenter::class);
        $report = $this->houseDailyReportRepository->find((int) $reportId);

        return inertia(
            'HouseDailyReports/Edit', [
            'report' => $report,
            ]
        );
    }

    /**
     * @param HouseDailyReportRequest $request
     * @param int                     $reportId
     *
     * @return RedirectResponse
     */
    public function update(HouseDailyReportRequest $request, $reportId)
    {
        $request->setFormForUpdate()->update((int) $reportId);

        session()->flash("success", "House Daily Report Updated");

        return redirect()->route('admin.house-daily-reports.index', Response::HTTP_SEE_OTHER);
    }

    /**
     * @param int $dailyReportId
     *
     * @return RedirectResponse
     */
    public function delete($dailyReportId)
    {
        try {
            $report = $this->houseDailyReportRepository->find((int) $dailyReportId);
            $report->delete();

            session()->flash("success", "House Daily Report deleted.");
        } catch (ModelNotFoundException $exception) {
            logger()->error($exception->getMessage());
            session()->flash("error", "House Daily Report could not be deleted.");
        }

        return redirect()->back();
    }
}
