<?php

namespace App\Application\Front\Controllers;

use App\Application\Front\Requests\ApplicationFormSaveRequest;
use App\Application\Front\Requests\EnumeratorShortlistStatusRequest;
use App\Application\Front\Support\ApplicationFormOptions;
use App\Application\Front\Support\Exceptions\FormNonEditableException;
use App\Domain\Applicants\Models\Applicant;
use App\Domain\Applicants\Presenters\FrontFormPresenter;
use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Domain\Applicants\Repositories\ShortListedApplicantRepository;
use App\Domain\Applications\Actions\SaveApplication;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\FrontController;
use App\Infrastructure\Support\Helper;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Class ApplicationController
 *
 * @package App\Application\Front\Controllers
 */
class ApplicationController extends FrontController
{
    /**
     * @var ApplicantRepository
     */
    protected ApplicantRepository $applicantRepository;

    /**
     * @var ShortListedApplicantRepository
     */
    protected ShortListedApplicantRepository $shortListedApplicantRepository;

    /**
     * ApplicationController constructor.
     *
     * @param ApplicantRepository            $applicantRepository
     * @param ShortListedApplicantRepository $shortListedApplicantRepository
     */
    public function __construct(ApplicantRepository $applicantRepository, ShortListedApplicantRepository $shortListedApplicantRepository)
    {
        $this->applicantRepository            = $applicantRepository;
        $this->shortListedApplicantRepository = $shortListedApplicantRepository;
    }

    /**
     * @return Application|Factory|View
     * @throws FormNonEditableException
     */
    public function showForm()
    {
        AuthHelper::guardEditableForm();

        /** @var Applicant $applicant */
        $applicant = AuthHelper::currentUser(Guard::APPLICANT);
        $this->applicantRepository->setPresenter(FrontFormPresenter::class);

        $data              = ApplicationFormOptions::formOptions();
        $data['applicant'] = $this->applicantRepository->find($applicant->id);

        return view('front.application.form', $data);
    }

    /**
     * @param ApplicationFormSaveRequest $request
     * @param SaveApplication            $saveApplication
     *
     * @return JsonResponse
     * @throws FormNonEditableException
     */
    public function saveForm(ApplicationFormSaveRequest $request, SaveApplication $saveApplication): JsonResponse
    {
        AuthHelper::guardEditableForm();

        try {
            [$applicant, $application] = $request->prepare();
            $saveApplication->save($applicant, $application);
        } catch (\Exception | \Throwable $exception) {
            Helper::logException($exception);

            return $this->sendError('Application Form Submit failed', Response::HTTP_BAD_REQUEST);
        }

        return $this->sendResponse(
            [
                'redirection_url' => route('front.application.form.preview'),
            ],
            'Application form saved'
        );
    }

    /**
     * @param EnumeratorShortlistStatusRequest $request
     *
     * @return JsonResponse
     */
    public function checkStatus(EnumeratorShortlistStatusRequest $request)
    {
        $shortlistedApplicant = null;

        $this->shortListedApplicantRepository->with(['municipality', 'municipality.district', 'applicant'])->byRole($request->get('role', ApplicationType::ENUMERATOR));

        if ( $request->has('submission_number') ) {
            $shortlistedApplicant = $this->shortListedApplicantRepository->isShortlistedBySubmissionNumber($request->get('submission_number'))->all()->first();
        }

        if ( $request->has('mobile_number') ) {
            $shortlistedApplicant = $this->shortListedApplicantRepository->isShortlistedByMobileNumber($request->get('mobile_number'))->all()->first();
        }

        if ( !$shortlistedApplicant || !$shortlistedApplicant->is_shortlisted ) {
            return $this->sendError('Record not found', Response::HTTP_NOT_FOUND);
        }

        return $this->sendResponse(
            [
                'applicant' => $shortlistedApplicant,
            ],
            'Application form saved'
        );

    }
}
