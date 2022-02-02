<?php


namespace App\Application\Api\Controllers;


use App\Application\Api\Requests\UserProfileUpdateRequest;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\ApiController;
use Illuminate\Http\Request;

/**
 * Class UserProfileApiController
 * @package App\Application\Api\Controllers
 */
class UserProfileApiController extends ApiController
{
    /**
     * @var \App\Domain\Users\Repositories\UserRepository
     */
    private UserRepository $userRepository;

    /**
     * UserProfileApiController constructor.
     *
     * @param \App\Domain\Users\Repositories\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function show(Request $request)
    {
        /** @var User $user */
        $user = $this->userRepository->with('enumerators')->find($request->user()->id);

        return $user->setHidden(['metadata']);
    }

    /**
     * @param UserProfileUpdateRequest $request
     *
     */
    public function update(UserProfileUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        /** @var \App\Domain\Users\Models\User $authUser */
        $authUser = AuthHelper::currentUser(Guard::API);
        try {
            /** @var User $user */
            $user = $request->prepareData()->update($authUser->id);
            $user->setHidden(['metadata']);

            return $this->sendResponse(
                [
                    'user' => $user,
                ],
                'Profile updated'
            );
        } catch ( \Exception $e ) {
            logger()->error($e);

            return $this->sendError("Error Updating Profile");
        }
    }
}
