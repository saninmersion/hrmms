<?php

namespace App\Application\Front\Controllers;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Applicants\Models\Applicant;
use App\Domain\Applicants\Presenters\FormPreviewPresenter;
use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\Controller\FrontController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;

/**
 * Class ApplicationPreviewController
 * @package App\Application\Front\Controllers
 */
class ApplicationPreviewController extends FrontController
{
    /**
     * @param ApplicantRepository $applicantRepository
     * @param DistrictRepository  $districtRepository
     *
     * @return Factory|View
     */
    public function __invoke(ApplicantRepository $applicantRepository, DistrictRepository $districtRepository)
    {
        /** @var Applicant $currentApplicant */
        $currentApplicant = auth(Guard::APPLICANT)->user();
        $newDistricts     = $districtRepository->allDistrictsWithMunicipalitiesOptions();
        $oldDistricts     = $districtRepository->allDistrictForOptions();

        $applicantRepository->setPresenter(FormPreviewPresenter::class);
        $applicant = $applicantRepository->with('application')->find($currentApplicant->id);

        $applicationSuccess = session()->get('application-success', false);
        $successMessage     = '';
        if ( $applicationSuccess ) {
            session()->remove('application-success');
            $applicationFor     = Arr::get($applicant, 'application.application_for');
            $applicationForText = trans("application.success-message-post.{$applicationFor}");
            $successMessage     = trans(
                'application.success-message',
                [
                    'applicationFor'   => "<strong>{$applicationForText}</strong>",
                    'submissionNumber' => "<strong>{$applicant['submission_number']}</strong>",
                ]
            );
        }

        return view(
            'front.application.preview',
            compact(
                'applicant',
                'oldDistricts',
                'newDistricts',
                'successMessage'
            )
        );
    }
}
