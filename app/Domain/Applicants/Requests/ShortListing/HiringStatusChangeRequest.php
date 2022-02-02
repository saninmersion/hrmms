<?php

namespace App\Domain\Applicants\Requests\ShortListing;

use App\Domain\Applicants\Models\ShortListedApplicant;
use App\Domain\Applicants\Repositories\ShortListedApplicantRepository;
use App\Infrastructure\Constants\HiringStatus;
use App\Infrastructure\Support\Requests\WebFormRequest;

class HiringStatusChangeRequest extends WebFormRequest
{
    /**
     * @var ShortListedApplicantRepository
     */
    protected ShortListedApplicantRepository $shortListedApplicantRepository;

    /**
     * @var array
     */
    private array $data;

    /**
     * HiringStatusChangeRequest constructor.
     *
     * @param ShortListedApplicantRepository $shortListedApplicantRepository
     */
    public function __construct(ShortListedApplicantRepository $shortListedApplicantRepository)
    {
        $this->shortListedApplicantRepository = $shortListedApplicantRepository;
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'status' => 'Status',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $hiringStatus = implode(',', HiringStatus::all());

        return [
            'status' => "required|in:{$hiringStatus}",
        ];
    }

    /**
     * @return $this
     */
    public function setForm(): self
    {
        $this->data = [
            'hiring_status' => $this->input('status'),
            'metadata'      => [
                'comments' => $this->input('comments'),
            ],
        ];

        return $this;
    }

    /**
     * @param int    $shortListedApplicantId
     * @param string $role
     *
     * @return mixed
     */
    public function update(int $shortListedApplicantId, string $role)
    {
        /** @var ShortListedApplicant $applicant */
        $applicant = $this->shortListedApplicantRepository->findWhere(
            [
                'applicant_id' => $shortListedApplicantId,
                'role'         => $role,
            ]
        )->first();

        return $this->shortListedApplicantRepository->update($this->data, $applicant->id);
    }
}
