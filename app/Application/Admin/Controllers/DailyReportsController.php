<?php

namespace App\Application\Admin\Controllers;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\CensusOffices\Repositories\CensusOfficeRepository;
use App\Domain\DailyReports\Criteria\DailyReportListCriteria;
use App\Domain\DailyReports\Presenters\DailyReportListPresenter;
use App\Domain\DailyReports\Repositories\DailyReportRepository;
use App\Domain\DailyReports\Requests\DailyReportRequest;
use App\Domain\Enumerators\Repositories\EnumeratorRepository;
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
 * Class DailyReportsController
 *
 * @package App\Application\Admin\Controllers
 */
class DailyReportsController extends AdminController
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
     * @var CensusOfficeRepository
     */
    protected CensusOfficeRepository $censusOfficeRepository;
    /**
     * @var EnumeratorRepository
     */
    private EnumeratorRepository $enumeratorRepository;

    /**
     * DailyReportsController constructor.
     *
     * @param DailyReportRepository  $dailyReportRepository
     * @param DistrictRepository     $districtRepository
     * @param CensusOfficeRepository $censusOfficeRepository
     */
    public function __construct(DailyReportRepository $dailyReportRepository, DistrictRepository $districtRepository, CensusOfficeRepository $censusOfficeRepository, EnumeratorRepository $enumeratorRepository)
    {
        $this->dailyReportRepository  = $dailyReportRepository;
        $this->districtRepository     = $districtRepository;
        $this->censusOfficeRepository = $censusOfficeRepository;
        $this->enumeratorRepository   = $enumeratorRepository;
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

        $this->dailyReportRepository->with(['district', 'censusOffice', 'supervisor'])->setPresenter(DailyReportListPresenter::class);
        $this->dailyReportRepository->pushCriteria(new DailyReportListCriteria($queries));

        if ( in_array($user->role, [AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN]) ) {
            $dailyReports = $this->dailyReportRepository->paginate($perPage);
        } else {
            $dailyReports = $this->dailyReportRepository->byCreatedBy($user->id)->paginate($perPage);
        }

        $meta = array_pop($dailyReports);

        return inertia(
            'DailyReports/List', [
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
        /** @var User $user */
        $user        = AuthHelper::currentUser();
        $enumerators = [];

        if ( in_array($user->role, [AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN]) ) {
            $enumerators = $this->enumeratorRepository->all(['id', 'name']);
        } else {
            $enumerators = $this->enumeratorRepository->bySupervisor($user->id)->all(['id', 'name']);
        }

        return inertia(
            'DailyReports/Create', [
            'enumerators' => $enumerators,
            ]
        );
    }

    /**
     * @param DailyReportRequest $request
     *
     * @return RedirectResponse
     */
    public function store(DailyReportRequest $request)
    {
        $request->setForm()->store();

        session()->flash("success", "Daily Report Saved");

        return redirect()->route('admin.daily-reports.index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param int $reportId
     *
     * @return \Inertia\Response|ResponseFactory
     */
    public function edit($reportId)
    {
        /** @var User $user */
        $user        = AuthHelper::currentUser();
        $enumerators = [];

        if ( in_array($user->role, [AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN]) ) {
            $enumerators = $this->enumeratorRepository->all(['id', 'name']);
        } else {
            $enumerators = $this->enumeratorRepository->bySupervisor($user->id)->all(['id', 'name']);
        }
        $this->dailyReportRepository->setPresenter(DailyReportListPresenter::class);
        $report = $this->dailyReportRepository->find((int) $reportId);

        return inertia(
            'DailyReports/Edit', [
            'report'      => $report,
            'enumerators' => $enumerators,
            ]
        );
    }

    /**
     * @param DailyReportRequest $request
     * @param int                $reportId
     *
     * @return RedirectResponse
     */
    public function update(DailyReportRequest $request, $reportId)
    {
        $request->setFormForUpdate()->update((int) $reportId);

        session()->flash("success", "Daily Report Updated");

        return redirect()->route('admin.daily-reports.index', Response::HTTP_SEE_OTHER);
    }

    /**
     * @param int $dailyReportId
     *
     * @return RedirectResponse
     */
    public function delete($dailyReportId)
    {
        try {
            $report = $this->dailyReportRepository->find((int) $dailyReportId);
            $report->delete();

            session()->flash("success", "Daily Report deleted.");
        } catch (ModelNotFoundException $exception) {
            logger()->error($exception->getMessage());
            session()->flash("error", "Daily Report could not be deleted.");
        }

        return redirect()->back();
    }
}
