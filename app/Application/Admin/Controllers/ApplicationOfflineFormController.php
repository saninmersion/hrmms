<?php

namespace App\Application\Admin\Controllers;

use App\Application\Front\Support\ApplicationFormOptions;
use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Applicants\Exceptions\ApplicantCitizenshipPhoneMismatchException;
use App\Domain\Applicants\Presenters\FrontFormPresenter;
use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Domain\Applicants\Requests\ApplicantCheckRequest;
use App\Domain\Applications\Actions\SaveApplication;
use App\Domain\Applications\Requests\OfflineApplicationRequest;
use App\Infrastructure\Support\Controller\AdminController;

class ApplicationOfflineFormController extends AdminController
{
    /**
     * @var ApplicantRepository
     */
    protected ApplicantRepository $applicantRepository;

    /**
     * @var DistrictRepository
     */
    protected DistrictRepository $districtRepository;

    /**
     * ApplicationOfflineFormController constructor.
     *
     * @param ApplicantRepository $applicantRepository
     * @param DistrictRepository  $districtRepository
     */
    public function __construct(ApplicantRepository $applicantRepository, DistrictRepository $districtRepository)
    {
        $this->applicantRepository = $applicantRepository;
        $this->districtRepository  = $districtRepository;
    }

    /**
     * @param int  $applicantId
     * @param bool $status
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function showForm($applicantId, bool $status = null)
    {
        $this->applicantRepository->setPresenter(FrontFormPresenter::class);
        $applicant      = $this->applicantRepository->find((int) $applicantId);
        $data           = ApplicationFormOptions::formOptions();
        $mediaUploadUrl = route('admin.applications.files.upload');

        $data['mediaUploadUrl'] = $mediaUploadUrl;
        $data['applicant']      = $applicant;
        $data['foundStatus']    = $status;

        return inertia('Applications/CreateApplication', $data);
    }

    /**
     * @param DistrictRepository $districtRepository
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function showApplicantCheckForm(DistrictRepository $districtRepository)
    {
        $districts = $districtRepository->allDistrictForOptions();

        return inertia('Applications/CheckApplicant', compact('districts'));
    }

    /**
     * @param ApplicantCheckRequest $applicantCheckRequest
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkApplicant(ApplicantCheckRequest $applicantCheckRequest)
    {
        try {
            [$applicantId, $status] = $applicantCheckRequest->setForm()->checkApplicant();
        }catch (ApplicantCitizenshipPhoneMismatchException $exception) {
            session()->flash('error', $exception->getMessage());

            return redirect()->back(\Illuminate\Http\Response::HTTP_SEE_OTHER);
        }
        if( $status) {
            session()->flash('success', 'User with the same details was already found in the system. Please edit application and save.');
        }

        return redirect()->route('admin.applications.offline-form.show', $applicantId, \Illuminate\Http\Response::HTTP_SEE_OTHER);
    }

    /**
     * @param OfflineApplicationRequest $offlineApplicationRequest
     * @param SaveApplication           $saveApplication
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function saveForm(OfflineApplicationRequest $offlineApplicationRequest, SaveApplication $saveApplication)
    {
        try {
            [$applicant, $application] = $offlineApplicationRequest->prepare();
            $saveApplication->save($applicant, $application);

        } catch (\Exception | \Throwable $exception) {
            \App\Infrastructure\Support\Helper::logException($exception);

            return $this->sendError('Application Form Submit failed', \Illuminate\Http\Response::HTTP_BAD_REQUEST);
        }

        session()->flash('success', 'Application was successfully saved.');

        return redirect()->route('admin.applications.offline.index', [], \Illuminate\Http\Response::HTTP_SEE_OTHER);
    }
}
