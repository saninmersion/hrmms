<?php

namespace App\Domain\Applicants\Actions;

use App\Domain\Applicants\DTO\NewApplicantLoginAttemptDTO;
use App\Domain\Applicants\Models\Applicant;
use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Domain\Applicants\Support\Exceptions\LoginFailedException;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\BikramSambat\BikramSambat;
use App\Infrastructure\Support\Helper;
use Exception;

/**
 * Class NewApplicantLogin
 * @package App\Domain\Applicants\Actions
 */
class NewApplicantLogin
{
    /**
     * @var ApplicantRepository
     */
    protected ApplicantRepository $repository;

    /**
     * NewApplicantLogin constructor.
     *
     * @param ApplicantRepository $repository
     */
    public function __construct(ApplicantRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param NewApplicantLoginAttemptDTO $data
     *
     * @return void
     * @throws LoginFailedException
     * @throws Exception
     */
    public function attempt(NewApplicantLoginAttemptDTO $data): void
    {
        if ( !$this->validateAge($data->dob, $data->gender) ) {
            throw new LoginFailedException('age');
        }

        try {
            /** @var Applicant $applicant */
            $applicant = $this->repository->create(
                [
                    'dob_bs'                           => $data->dob,
                    'citizenship_number'               => $data->citizenship,
                    'citizenship_issued_district_code' => $data->district,
                    'mobile_number'                    => $data->mobileNumber,
                    'details'                          => [
                        'gender' => $data->gender,
                    ],
                ]
            );
        } catch (Exception $exception) {
            throw new LoginFailedException($exception->getMessage());
        }

        $this->login($applicant);
    }

    /**
     * @param Applicant $applicant
     */
    public function login(Applicant $applicant): void
    {
        auth()->guard(Guard::APPLICANT)->login($applicant);
    }

    /**
     * @param string $dob
     * @param string $gender
     *
     * @return bool
     * @throws Exception
     */
    protected function validateAge(string $dob, string $gender): bool
    {
        $ageLimit = config('config.age-limit');
        $dateAd   = BikramSambat::bsToAd($dob);
        if ( !$dateAd ) {
            return false;
        }

        $age = Helper::deadline()->diffInYears($dateAd);

        return $age >= $ageLimit[$gender][0] && $age <= $ageLimit[$gender][1];
    }
}
