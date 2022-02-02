<?php


namespace App\Application\Admin\Controllers;


use App\Domain\Enumerators\Presenters\EnumeratorDetailPresenter;
use App\Domain\Enumerators\Repositories\EnumeratorRepository;
use App\Domain\Enumerators\Requests\EnumeratorRequest;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\General;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnumeratorController extends AdminController
{
    /**
     * @var \App\Domain\Enumerators\Repositories\EnumeratorRepository
     */
    private EnumeratorRepository $enumeratorRepository;

    public function __construct(EnumeratorRepository $enumeratorRepository)
    {
        $this->enumeratorRepository = $enumeratorRepository;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = AuthHelper::currentUser();

        $queries = $request->all();
        $perPage = $request->input('per_page', General::paginateDefault());

        $this->enumeratorRepository->setPresenter(EnumeratorDetailPresenter::class);
        $enumerators = $this->enumeratorRepository->bySupervisor(optional($user)->id)->paginate($perPage);

        $meta = array_pop($enumerators);

        return inertia(
            'Enumerators/List',
            [
                'enumerators' => $enumerators,
                'pagination'  => $meta['pagination'],
                'queries'     => $queries ?: null,
            ]
        );
    }

    /**
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        return inertia('Enumerators/Create');
    }

    /**
     * @param \App\Domain\Enumerators\Requests\EnumeratorRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EnumeratorRequest $request): RedirectResponse
    {
        $request->setForm()->store();

        session()->flash("success", "Enumerator Saved");

        return redirect()->route('admin.enumerators.index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param int $enumeratorId
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit($enumeratorId)
    {
        $this->enumeratorRepository->setPresenter(EnumeratorDetailPresenter::class);
        $enumerator = $this->enumeratorRepository->find((int) $enumeratorId);

        return inertia('Enumerators/Edit', compact('enumerator'));
    }

    /**
     * @param \App\Domain\Enumerators\Requests\EnumeratorRequest $request
     * @param int                                                $enumeratorId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EnumeratorRequest $request, $enumeratorId): RedirectResponse
    {
        $request->setFormForUpdate()->update($enumeratorId);

        session()->flash("success", "Enumerator Updated");

        return redirect()->route('admin.enumerators.index', Response::HTTP_SEE_OTHER);
    }
}
