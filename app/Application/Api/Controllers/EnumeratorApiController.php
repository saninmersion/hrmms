<?php


namespace App\Application\Api\Controllers;


use App\Application\Api\Requests\EnumeratorCreateApiRequest;
use App\Application\Api\Requests\EnumeratorUpdateApiRequest;
use App\Domain\Enumerators\Presenters\EnumeratorApiPresenter;
use App\Domain\Enumerators\Presenters\EnumeratorDetailPresenter;
use App\Domain\Enumerators\Repositories\EnumeratorRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\ApiController;

class EnumeratorApiController extends ApiController
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /** @var User $user */
        $user = AuthHelper::currentUser(Guard::API);

        try {
            $this->enumeratorRepository->setPresenter(EnumeratorApiPresenter::class);
            $enumerators = $this->enumeratorRepository->bySupervisor(optional($user)->id)->all();

            return $this->sendResponse(
                [
                    'enumerators' => $enumerators,
                ],
                'List Of Enumerators'
            );
        } catch ( \Exception $exception ) {

            return $this->sendError("Error fetching Enumerator List");
        }
    }

    /**
     * @param \App\Application\Api\Requests\EnumeratorCreateApiRequest $enumeratorCreateApiRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EnumeratorCreateApiRequest $enumeratorCreateApiRequest)
    {
        try {
            $enumerator = $enumeratorCreateApiRequest->prepareData()->store();

            return $this->sendResponse(
                [
                    'enumerator' => $enumerator,
                ],
                'Enumerator Created'
            );
        } catch ( \Exception $exception ) {
            return $this->sendError($exception->getMessage());
        }
    }

    /**
     * @param \App\Application\Api\Requests\EnumeratorUpdateApiRequest $enumeratorUpdateApiRequest
     * @param int                                                      $enumeratorId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EnumeratorUpdateApiRequest $enumeratorUpdateApiRequest, $enumeratorId)
    {
        try {
            $enumerator = $enumeratorUpdateApiRequest->prepareData()->update((int) $enumeratorId);

            return $this->sendResponse(
                [
                    'enumerator' => $enumerator,
                ],
                'Enumerator Updated'
            );
        } catch ( \Exception $exception ) {
            return $this->sendError($exception->getMessage());
        }
    }
}
