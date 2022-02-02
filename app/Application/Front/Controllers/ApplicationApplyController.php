<?php

namespace App\Application\Front\Controllers;

use App\Application\Front\Support\Exceptions\FormNonEditableException;
use App\Domain\Applicants\Models\Applicant;
use App\Domain\Applicants\Presenters\FrontFormPresenter;
use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Domain\Applicants\Validators\ApplicationSubmissionValidator;
use App\Domain\Applications\Models\Application;
use App\Domain\Applications\Repositories\ApplicationRepository;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Constants\StatusTypes;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\FrontController;
use App\Infrastructure\Support\Helper;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * Class ApplicationApplyController
 * @package App\Application\Front\Controllers
 */
class ApplicationApplyController extends FrontController
{
    /**
     * @var ApplicantRepository
     */
    protected ApplicantRepository $applicantRepository;

    /**
     * ApplicationApplyController constructor.
     *
     * @param ApplicantRepository $applicantRepository
     */
    public function __construct(ApplicantRepository $applicantRepository)
    {
        $this->applicantRepository = $applicantRepository;
    }

    /**
     * @param ApplicationRepository          $applicationRepository
     * @param ApplicationSubmissionValidator $formValidator
     *
     * @return JsonResponse
     * @throws FormNonEditableException
     */
    public function __invoke(ApplicationRepository $applicationRepository, ApplicationSubmissionValidator $formValidator)
    {
        AuthHelper::guardEditableForm();

        try {
            /** @var Applicant $applicant */
            $applicant = AuthHelper::currentUser(Guard::APPLICANT);
            $this->applicantRepository->setPresenter(FrontFormPresenter::class);

            $inputs    = $this->applicantRepository->find($applicant->id);
            $validator = $formValidator->makeValidator($inputs);

            if ( $validator->fails() ) {
                throw new ValidationException($validator);
            }

            /** @var Application $application */
            $application = $applicant->application;
            $applicationRepository->update(
                [
                    'status'           => StatusTypes::APPLICATION_SUBMITTED,
                    'application_date' => $application->application_date ?? now(),
                ],
                $application->id
            );
        } catch (ValidationException $exception) {
            Helper::withErrors($exception->errors());

            return $this->sendError(route('front.application.form'), 422);
        } catch (Exception $exception) {
            logger()->error($exception);

            return $this->sendError('There was a problem submitting application.');
        }

        session()->put('application-success', true);

        return $this->sendResponse(
            [
                'redirection_url' => route('front.application.form.preview'),
            ],
            'Application form was submitted.'
        );
    }
}
