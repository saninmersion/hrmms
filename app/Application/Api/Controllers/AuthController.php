<?php

namespace App\Application\Api\Controllers;

use App\Application\Api\Requests\LoginRequest;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Support\Controller\ApiController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthController
 *
 * @package App\Application\Api\Controllers
 */
class AuthController extends ApiController
{
    protected UserRepository $userRepository;

    /**
     * AuthController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function token(LoginRequest $request)
    {
        /** @var User $user */
        $user = $this->userRepository->with('enumerators')->findWhere(['username' => $request->username])->first()->setHidden(['metadata']);

        if ( !isset($user->username) || !Hash::check($request->password, $user->password) ) {
            return response()->json(
                [
                    'errors' => [
                        'username' => ['The provided credentials are incorrect.'],
                    ],
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ( $user->role !== AuthRoles::SUPERVISOR ) {
            return response()->json(
                [
                    'errors' => [
                        'username' => ['Only supervisors can login through mobile application.'],
                    ],
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->sendResponse(['user' => $user, 'token' => $user->createToken(now())->plainTextToken]);
    }
}
