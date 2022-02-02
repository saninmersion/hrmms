<?php

namespace App\Domain\Applicants\Requests;

use App\Domain\Applicants\Exceptions\ApplicantCitizenshipPhoneMismatchException;
use App\Domain\Applicants\Models\Applicant;
use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Domain\Applications\Repositories\ApplicationRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Constants\StatusTypes;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Requests\WebFormRequest;
use App\Infrastructure\Support\Rules\Gender;
use App\Infrastructure\Support\Rules\MobileNumber;
use App\Infrastructure\Support\Rules\NepaliDate;

class ApplicantCheckRequest extends WebFormRequest
{
    /**
     * @var ApplicantRepository
     */
    protected ApplicantRepository $applicantRepository;
    /**
     * @var ApplicationRepository
     */
    protected ApplicationRepository $applicationRepository;
    private array $data;

    /**
     * ApplicantCheckRequest constructor.
     *
     * @param ApplicantRepository   $applicantRepository
     * @param ApplicationRepository $applicationRepository
     */
    public function __construct(ApplicantRepository $applicantRepository, ApplicationRepository $applicationRepository)
    {
        $this->applicantRepository   = $applicantRepository;
        $this->applicationRepository = $applicationRepository;
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'dob'                  => trans('application.date_of_birth'),
            'citizenship_number'   => trans('application.Nepali Citizenship No'),
            'citizenship_district' => trans('application.citizenship_district'),
            'mobile_number'        => trans('application.mobile_number'),
            'gender'               => trans('application.gender'),
        ];
    }

    public function rules(): array
    {
        $districtTable = DBTables::DISTRICTS;

        return [
            'dob'                  => ['required', new NepaliDate()],
            'citizenship_number'   => [
                'required',
                'string',
                'max:255',
            ],
            'citizenship_district' => "required|exists:{$districtTable},code",
            'mobile_number'        => ['required', new MobileNumber()],
            'gender'               => ['required', new Gender()],
        ];
    }

    /**
     * @return $this
     */
    public function setForm(): self
    {
        $this->data = [
            'dob_bs'                           => $this->input('dob'),
            'citizenship_number'               => $this->input('citizenship_number'),
            'citizenship_issued_district_code' => $this->input('citizenship_district'),
            'mobile_number'                    => $this->input('mobile_number'),
            'details'                          => [
                'gender' => $this->input('gender'),
            ],
        ];

        return $this;
    }


    /**
     * @return array
     * @throws ApplicantCitizenshipPhoneMismatchException
     */
    public function checkApplicant(): array
    {
        $applicants = collect(
            $this->applicantRepository->findWhereCitizenshipOrPhone($this->data['citizenship_number'], $this->data['mobile_number'])->all()
        );

        /** @var Applicant $applicant */
        $applicant   = $applicants->first();
        $foundStatus = true;
        if ($applicants->isNotEmpty() && $applicant->citizenship_number !== $this->data['citizenship_number'] ) {
            throw new ApplicantCitizenshipPhoneMismatchException("The following phone number($applicant->mobile_number) is associated with another citizenship number($applicant->citizenship_number).");
        }

        if ($applicants->isNotEmpty() && $applicant->mobile_number !== $this->data['mobile_number'] ) {
            throw new ApplicantCitizenshipPhoneMismatchException("The following citizenship number($applicant->citizenship_number) is associated with another mobile number($applicant->mobile_number).");
        }
        /** @var User $currentUser */
        $currentUser = AuthHelper::currentUser();

        if ( $applicants->isEmpty() ) {
            $applicant = $this->applicantRepository->create($this->data);
            $applicant->application()->updateOrCreate(
                ['applicant_id' => $applicant->id],
                [
                    'for_enumerator'   => false,
                    'for_supervisor'   => false,
                    'application_date' => now(),
                    'is_offline'       => true,
                    'entered_by_id'    => $currentUser->id,
                    'status'           => StatusTypes::APPLICATION_DRAFT,
                ]
            );
            $foundStatus = false;
        }

        return [$applicant->id, $foundStatus];
    }
}
