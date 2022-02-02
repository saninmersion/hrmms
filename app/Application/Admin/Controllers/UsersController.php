<?php

namespace App\Application\Admin\Controllers;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\CensusOffices\Repositories\CensusOfficeRepository;
use App\Domain\Users\Actions\CreateUser;
use App\Domain\Users\Actions\DeleteUser;
use App\Domain\Users\Actions\UpdateUser;
use App\Domain\Users\Criteria\UserRequestCriteria;
use App\Domain\Users\Presenters\AuthUserPresenter;
use App\Domain\Users\Repositories\UserRepository;
use App\Domain\Users\Requests\UserStoreRequest;
use App\Domain\Users\Requests\UserUpdateRequest;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\General;
use App\Infrastructure\Constants\UserStatus;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class UsersController
 * @package App\Application\Admin\Controllers
 */
class UsersController extends AdminController
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;
    /**
     * @var DistrictRepository
     */
    protected DistrictRepository $districtRepository;

    /**
     * @var CensusOfficeRepository
     */
    protected CensusOfficeRepository $censusOfficeRepository;

    /**
     * UsersController constructor.
     *
     * @param UserRepository         $userRepository
     * @param DistrictRepository     $districtRepository
     * @param CensusOfficeRepository $censusOfficeRepository
     */
    public function __construct(UserRepository $userRepository, DistrictRepository $districtRepository, CensusOfficeRepository $censusOfficeRepository)
    {
        $this->userRepository         = $userRepository;
        $this->districtRepository     = $districtRepository;
        $this->censusOfficeRepository = $censusOfficeRepository;
    }

    /**
     * @param Request $request
     *
     * @return Response|ResponseFactory
     * @throws RepositoryException
     */
    public function index(Request $request)
    {
        $queries = $request->all();
        $perPage = $request->input('per_page', General::paginateDefault());
        $roles   = AuthRoles::assignable();

        $this->userRepository->setPresenter(AuthUserPresenter::class);
        $this->userRepository->pushCriteria(new UserRequestCriteria($queries));
        $users = $this->userRepository->exceptRoles([AuthRoles::SUPER_ADMIN])->exceptStatus([UserStatus::STATUS_INACTIVE])->with("district")->paginate($perPage);
        $meta  = array_pop($users);

        return inertia(
            'Users/List',
            [
                'users'      => $users,
                'options'    => [
                    'roles' => $roles,
                ],
                'queries'    => empty($queries) ? null : $queries,
                'pagination' => $meta['pagination'],
            ]
        );
    }

    /**
     * @param Request $request
     *
     * @return Response|ResponseFactory
     * @throws RepositoryException
     */
    public function indexInactiveMonitors(Request $request)
    {
        $queries           = $request->all();
        $queries['role']   = AuthRoles::MONITORING;
        $queries['status'] = UserStatus::STATUS_INACTIVE;
        $perPage           = $request->input('per_page', General::paginateDefault());

        $this->userRepository->setPresenter(AuthUserPresenter::class);
        $this->userRepository->pushCriteria(new UserRequestCriteria($queries));
        $users = $this->userRepository->byRole(AuthRoles::MONITORING)->byStatus(UserStatus::STATUS_INACTIVE)->with("district")->paginate($perPage);
        $meta  = array_pop($users);

        return inertia(
            'Users/ListInactive',
            [
                'users'      => $users,
                'queries'    => empty($queries) ? null : $queries,
                'pagination' => $meta['pagination'],
            ]
        );
    }

    /**
     * @return Response|ResponseFactory
     */
    public function create()
    {
        $roles = AuthRoles::assignable();

        return inertia(
            'Users/CreateUser',
            [
                'roles'         => $roles,
                'districts'     => $this->districtRepository->allDistrictForOptions(),
                'censusOffices' => $this->censusOfficeRepository->all(),
            ]
        );
    }

    /**
     * @param UserStoreRequest $request
     * @param CreateUser       $createUser
     *
     * @return RedirectResponse
     */
    public function store(UserStoreRequest $request, CreateUser $createUser): RedirectResponse
    {
        $dto         = $request->formData();
        $dto->status = UserStatus::STATUS_ACTIVE;
        $user        = $createUser->create($dto);

        session()->flash("success", 'User created.');

        return redirect()->route('admin.users.show', $user->id, \Illuminate\Http\Response::HTTP_SEE_OTHER);
    }

    /**
     * @param int $userId
     *
     * @return ResponseFactory|Response
     */
    public function show($userId)
    {
        $this->userRepository->setPresenter(AuthUserPresenter::class);
        $userDetail = $this->userRepository->find((int) $userId);

        return inertia('Users/ShowUser', compact('userDetail'));

    }

    /**
     * @param int $userId
     *
     * @return Response|ResponseFactory
     */
    public function edit($userId)
    {
        $this->userRepository->setPresenter(AuthUserPresenter::class);
        $userDetail = $this->userRepository->find((int) $userId);

        return inertia(
            'Users/EditUser',
            [
                'userDetail'    => $userDetail,
                'roles'         => AuthRoles::assignable(),
                'districts'     => $this->districtRepository->allDistrictForOptions(),
                'censusOffices' => $this->censusOfficeRepository->all(),
            ]
        );
    }

    /**
     * @param int               $userId
     * @param UserUpdateRequest $request
     * @param UpdateUser        $user
     *
     * @return RedirectResponse
     */
    public function update($userId, UserUpdateRequest $request, UpdateUser $user)
    {
        $user->update($request->formData(), (int) $userId);

        session()->flash("success", 'User updated.');

        return redirect()->route('admin.users.show', $userId, \Illuminate\Http\Response::HTTP_SEE_OTHER);
    }

    /**
     * @param int        $userId
     * @param DeleteUser $user
     *
     * @return RedirectResponse
     */
    public function delete($userId, DeleteUser $user)
    {
        $user->delete((int) $userId);

        session()->flash('success', 'User deleted.');

        return redirect()->route('admin.users.index', [], \Illuminate\Http\Response::HTTP_SEE_OTHER);
    }
}
