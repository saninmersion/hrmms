<?php

namespace App\Domain\Applicants\Requests\ApplicationVerification;

use App\Domain\Applicants\Models\ApplicantVerification;
use App\Domain\Applicants\Repositories\ApplicantVerificationRepository;
use App\Infrastructure\Support\Requests\WebFormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class ApplicationVerificationRequest
 * @package App\Domain\Applicants\Requests\ApplicationVerification
 */
class ApplicationVerificationRequest extends WebFormRequest
{
    /**
     * @var ApplicantVerificationRepository
     */
    protected ApplicantVerificationRepository $verificationRepository;
    /**
     * @var array
     */
    private $data;

    /**
     * ApplicationVerificationRequest constructor.
     *
     * @param ApplicantVerificationRepository $verificationRepository
     */
    public function __construct(ApplicantVerificationRepository $verificationRepository)
    {
        $this->verificationRepository = $verificationRepository;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @return $this
     */
    public function set()
    {
        $this->data = [
            'applicant_id'        => $this->route('applicant_id'),
            'modified'            => $this->get('modified'),
            'checklist'           => $this->get('checklist'),
            'verification_status' => $this->get('verification_status'),
            'verifier_id'         => Auth::id(),
            'remarks'             => $this->get('remarks'),
            'rejection_reason'    => $this->get('rejection_reason'),
        ];

        return $this;

    }

    /**
     * @return ApplicantVerification
     */
    public function store()
    {
        $verifiedData = $this->verificationRepository->findWhere(['applicant_id' => $this->route('applicant_id')])->first();
        if ( $verifiedData ) {
            return $this->verificationRepository->update($this->data, $verifiedData->id);
        } else {
            return $this->verificationRepository->create($this->data);
        }
    }
}
