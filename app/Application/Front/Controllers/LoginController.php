<?php

namespace App\Application\Front\Controllers;

use App\Application\Front\Requests\ExistingLoginRequest;
use App\Application\Front\Requests\NewLoginRequest;
use App\Domain\Applicants\Actions\ExistingApplicantLogin;
use App\Domain\Applicants\Actions\NewApplicantLogin;
use App\Domain\Applicants\DTO\ApplicantLoginAttemptDTO;
use App\Domain\Applicants\DTO\NewApplicantLoginAttemptDTO;
use App\Domain\Applicants\Support\Exceptions\LoginFailedException;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\Controller\FrontController;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Class LoginController
 * @package App\Application\Front\Controllers
 */
class LoginController extends FrontController
{
    /**
     * @param NewLoginRequest   $request
     * @param NewApplicantLogin $applicantLogin
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function newLogin(NewLoginRequest $request, NewApplicantLogin $applicantLogin): JsonResponse
    {
        try {
            $applicantLogin->attempt(new NewApplicantLoginAttemptDTO($request->validated()));
        } catch (LoginFailedException $exception) {
            return $this->sendError($exception->getMessage(), $exception->getCode());
        }

        return $this->sendResponse(
            [
                'redirection_url' => route('front.application.form'),
            ]
        );
    }

    /**
     * @param string                 $type
     * @param ExistingLoginRequest   $request
     * @param ExistingApplicantLogin $applicantLogin
     *
     * @return JsonResponse
     */
    public function oldLogin($type, ExistingLoginRequest $request, ExistingApplicantLogin $applicantLogin): JsonResponse
    {
        try {
            $applicantLogin->attempt(new ApplicantLoginAttemptDTO($request->validated()));
        } catch (LoginFailedException $exception) {
            return $this->sendError($exception->getMessage(), $exception->getCode());
        }

        return $this->sendResponse(
            [
                'redirection_url' => route('front.application.form'),
                'type'            => $type,
            ]
        );
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->guard(Guard::APPLICANT)->logout();

        return $this->sendResponse(
            [
                'redirection_url' => route('front.home'),
            ]
        );

    }
}
