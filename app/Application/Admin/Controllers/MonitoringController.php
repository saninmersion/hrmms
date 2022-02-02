<?php

namespace App\Application\Admin\Controllers;

use App\Application\Admin\Support\MonitoringFormOptions;
use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Monitorings\Criteria\MonitoringListCriteria;
use App\Domain\Monitorings\Presenters\MonitoringFormPresenter;
use App\Domain\Monitorings\Presenters\MonitoringListPresenter;
use App\Domain\Monitorings\Repositories\MonitoringRepository;
use App\Domain\Monitorings\Requests\EnumeratorMonitoringFormRequest;
use App\Domain\Monitorings\Requests\MonitoringFormRequest;
use App\Domain\Monitorings\Requests\SupervisorMonitoringFormRequest;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\General;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Response;
use Inertia\ResponseFactory;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class MonitoringController
 * @package App\Application\Admin\Controllers
 */
class MonitoringController extends AdminController
{
    /**
     * @var DistrictRepository
     */
    protected DistrictRepository $districtRepository;
    /**
     * @var UserRepository
     */

    protected UserRepository $userRepository;
    /**
     * @var MonitoringRepository
     */
    protected MonitoringRepository $monitoringRepository;

    public function __construct(DistrictRepository $districtRepository, UserRepository $userRepository, MonitoringRepository $monitoringRepository)
    {
        $this->districtRepository   = $districtRepository;
        $this->userRepository       = $userRepository;
        $this->monitoringRepository = $monitoringRepository;
    }

    /**
     * @return Response|ResponseFactory
     * @throws RepositoryException
     */
    public function index(Request $request)
    {
        $queries = $request->all();

        /** @var User $user */
        $user = AuthHelper::currentUser();

        if ( $user->role === AuthRoles::DISTRICT_STAFFS ) {
            $queries['district_code']     = (string) $user->district_code;
            $queries['municipality_code'] = 'all';
        }

        $perPage = $request->input('per_page', General::paginateDefault());

        $this->monitoringRepository->pushCriteria(new MonitoringListCriteria($queries));

        $this->monitoringRepository->setPresenter(MonitoringListPresenter::class);
        $this->monitoringRepository->with(['monitoredBy', 'monitoredDistrict', 'monitoredMunicipality']);

        $districts = $this->districtRepository->allDistrictsWithMunicipalitiesOptions();

        /** @var User $user */
        $user = AuthHelper::currentUser();

        $monitorings = $this->getMonitorings($user, $perPage);

        $meta = array_pop($monitorings);

        return inertia(
            'Monitorings/List', [
                                             'monitorings' => $monitorings,
                                             'pagination'  => $meta['pagination'],
                                             'queries'     => $queries ?: null,
                                             'districts'   => $districts,
            ]
        );
    }

    /**
     * @param int $monitoringId
     *
     * @return Response|ResponseFactory|void
     */
    public function show($monitoringId)
    {
        $monitoring = $this->monitoringRepository->with(['monitoredBy', 'monitoredDistrict', 'monitoredMunicipality'])->find((int) $monitoringId);

        if ( $monitoring->monitoring_form == ApplicationConstants::MONITORING_FORM_OVERALL ) {
            return inertia(
                'Monitorings/OverallMonitoringDetail', [
                                                                    'monitoring' => $monitoring,
                ]
            );
        }

        if ( $monitoring->monitoring_form == ApplicationConstants::MONITORING_FORM_SUPERVISOR ) {
            return inertia(
                'Monitorings/SupervisorMonitoringDetail', [
                                                                       'monitoring' => $monitoring,
                ]
            );
        }

        if ( $monitoring->monitoring_form == ApplicationConstants::MONITORING_FORM_ENUMERATOR ) {
            return inertia(
                'Monitorings/EnumeratorMonitoringDetail', [
                                                                       'monitoring' => $monitoring,
                ]
            );
        }

        abort(404);
    }

    /**
     * @return Response|ResponseFactory
     */
    public function showOverallMonitoringForm()
    {
        $districts = $this->districtRepository->allDistrictsWithMunicipalitiesOptions();

        /** @var User $user */
        $user = AuthHelper::currentUser();

        $monitors = $this->getMonitors($user);

        $options = MonitoringFormOptions::overallFormOptions();

        return inertia(
            'Monitorings/OverallMonitoringFormAdd',
            compact(
                'districts',
                'monitors',
                'options'
            )
        );
    }

    /**
     * @param MonitoringFormRequest $request
     *
     * @return RedirectResponse
     */
    public function storeOverallMonitoringForm(MonitoringFormRequest $request)
    {
        $request->setOverallForm()->store();

        session()->flash("success", "Overall Monitoring Form Saved");

        return redirect()->route('admin.monitorings.index', [], \Illuminate\Http\Response::HTTP_SEE_OTHER);
    }

    /**
     * @return Response|ResponseFactory
     */
    public function showSupervisorMonitoringForm()
    {
        $districts = $this->districtRepository->allDistrictsWithMunicipalitiesOptions();

        /** @var User $user */
        $user = AuthHelper::currentUser();

        $monitors = $this->getMonitors($user);

        $options = MonitoringFormOptions::supervisorFormOptions();

        return inertia(
            'Monitorings/SupervisorMonitoringFormAdd',
            compact(
                'districts',
                'monitors',
                'options'
            )
        );
    }

    /**
     * @param SupervisorMonitoringFormRequest $request
     *
     * @return RedirectResponse
     */
    public function storeSupervisorMonitoringForm(SupervisorMonitoringFormRequest $request)
    {
        $request->setSupervisorForm()->store();

        session()->flash("success", "Supervisor Monitoring Form Saved");

        return redirect()->route('admin.monitorings.index', [], \Illuminate\Http\Response::HTTP_SEE_OTHER);
    }

    /**
     * @return Response|ResponseFactory
     */
    public function showEnumeratorMonitoringForm()
    {
        $districts = $this->districtRepository->allDistrictsWithMunicipalitiesOptions();

        /** @var User $user */
        $user     = AuthHelper::currentUser();
        $monitors = $this->getMonitors($user);

        $options = MonitoringFormOptions::enumeratorFormOptions();

        return inertia(
            'Monitorings/EnumeratorMonitoringFormAdd',
            compact(
                'districts',
                'monitors',
                'options'
            )
        );
    }

    /**
     * @param EnumeratorMonitoringFormRequest $request
     *
     * @return RedirectResponse
     */
    public function storeEnumeratorMonitoringForm(EnumeratorMonitoringFormRequest $request)
    {
        $request->setEnumeratorForm()->store();

        session()->flash("success", "Enumerator Monitoring Form Saved");

        return redirect()->route('admin.monitorings.index', [], \Illuminate\Http\Response::HTTP_SEE_OTHER);
    }

    /**
     * @param int $monitoringId
     *
     * @return Response|ResponseFactory
     */
    public function edit($monitoringId)
    {
        $this->monitoringRepository->with(['monitoredBy', 'monitoredDistrict', 'monitoredMunicipality']);
        $this->monitoringRepository->setPresenter(MonitoringFormPresenter::class);
        $monitoring = $this->monitoringRepository->find((int) $monitoringId);

        /** @var User $user */
        $user = AuthHelper::currentUser();

        if ( data_get($monitoring, 'monitoring_form') === ApplicationConstants::MONITORING_FORM_OVERALL ) {
            $districts = $this->districtRepository->allDistrictsWithMunicipalitiesOptions();

            $monitors = $this->getMonitors($user);

            $options = MonitoringFormOptions::overallFormOptions();

            return inertia(
                'Monitorings/OverallMonitoringFormEdit',
                compact('monitoring', 'districts', 'options', 'monitors')
            );
        }

        if ( data_get($monitoring, 'monitoring_form') === ApplicationConstants::MONITORING_FORM_SUPERVISOR ) {
            $districts = $this->districtRepository->allDistrictsWithMunicipalitiesOptions();

            $monitors = $this->getMonitors($user);

            $options = MonitoringFormOptions::supervisorFormOptions();

            return inertia(
                'Monitorings/SupervisorMonitoringFormEdit',
                compact('monitoring', 'districts', 'options', 'monitors')
            );
        }

        if ( data_get($monitoring, 'monitoring_form') === ApplicationConstants::MONITORING_FORM_ENUMERATOR ) {
            $districts = $this->districtRepository->allDistrictsWithMunicipalitiesOptions();

            $monitors = $this->getMonitors($user);

            $options = MonitoringFormOptions::enumeratorFormOptions();

            return inertia(
                'Monitorings/EnumeratorMonitoringFormEdit',
                compact('monitoring', 'districts', 'options', 'monitors')
            );
        }

        abort(\Illuminate\Http\Response::HTTP_NOT_FOUND);
    }

    /**
     * @param MonitoringFormRequest $request
     * @param int                   $monitoringId
     *
     * @return RedirectResponse
     */
    public function updateOverallMonitoringForm(MonitoringFormRequest $request, $monitoringId)
    {
        $request->setOverallFormForUpdate()->update((int) $monitoringId);

        session()->flash("success", "Overall Monitoring Form Updated");

        return redirect()->route('admin.monitorings.show', [$monitoringId], \Illuminate\Http\Response::HTTP_SEE_OTHER);
    }

    /**
     * @param EnumeratorMonitoringFormRequest $request
     * @param int                             $monitoringId
     *
     * @return RedirectResponse
     */
    public function updateEnumeratorMonitoringForm(EnumeratorMonitoringFormRequest $request, $monitoringId)
    {
        $request->setEnumeratorFormForUpdate()->update((int) $monitoringId);

        session()->flash("success", "Enumerator Monitoring Form Updated");

        return redirect()->route('admin.monitorings.show', [$monitoringId], \Illuminate\Http\Response::HTTP_SEE_OTHER);
    }

    /**
     * @param SupervisorMonitoringFormRequest $request
     * @param int                             $monitoringId
     *
     * @return RedirectResponse
     */
    public function updateSupervisorMonitoringForm(SupervisorMonitoringFormRequest $request, $monitoringId)
    {
        $request->setSupervisorFormForUpdate()->update((int) $monitoringId);

        session()->flash("success", "Supervisor Monitoring Form Updated");

        return redirect()->route('admin.monitorings.show', [$monitoringId], \Illuminate\Http\Response::HTTP_SEE_OTHER);
    }

    /**
     * @param User $user
     *
     * @return array
     */
    protected function getMonitors(User $user): array
    {
        if ( $user->role !== AuthRoles::OPERATOR ) {
            return [];
        }
        /** @var Collection $monitors */
        $monitors = $this->userRepository->byRole(AuthRoles::MONITORING)->all(['id', 'name']);

        return $monitors->toArray();
    }

    /**
     * @param User $user
     * @param int  $perPage
     *
     * @return mixed
     */
    private function getMonitorings(User $user, $perPage)
    {
        if ( in_array($user->role, [AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN]) ) {
            return $this->monitoringRepository->orderBy('created_at', 'desc')->paginate($perPage);
        }

        if ( $user->role === AuthRoles::OPERATOR ) {
            return $this->monitoringRepository->byEnteredBy($user->id)->orderBy('created_at', 'desc')->paginate(
                $perPage
            );
        }

        if ( $user->role === AuthRoles::DISTRICT_STAFFS ) {
            return $this->monitoringRepository->byDistrict($user->district_code)->orderBy('created_at', 'desc')->paginate($perPage);
        }

        return $this->monitoringRepository->byMonitoredBy($user->id)->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
