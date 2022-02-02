<?php

namespace App\Application\Admin\Controllers;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Applications\Requests\ApplicationRequest;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Http\JsonResponse;
use Inertia\Response;
use Inertia\ResponseFactory;

/**
 * Class ApplicationsController
 * @package App\Application\Admin\Controllers
 */
class ApplicationsController extends AdminController
{
    /**
     * @var DistrictRepository
     */
    protected DistrictRepository $districtRepository;

    /**
     * ApplicationsController constructor.
     *
     * @param DistrictRepository $districtRepository
     */
    public function __construct(DistrictRepository $districtRepository)
    {
        $this->districtRepository = $districtRepository;
    }

    /**
     * @return Response|ResponseFactory
     */
    public function create()
    {
        $districts = $this->districtRepository->allDistrictForOptions();

        return inertia(
            'Applications/CreateApplication',
            [
                'districts' => $districts,
            ]
        );
    }

    /**
     * @param ApplicationRequest $request
     */
    public function store(ApplicationRequest $request): JsonResponse
    {
        $application = $request->setData()->store();

        return $this->sendResponse($application, 'Application successfully created.', 201);
    }
}
