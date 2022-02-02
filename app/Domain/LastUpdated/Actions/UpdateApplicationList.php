<?php

namespace App\Domain\LastUpdated\Actions;

use App\Domain\Applicants\Repositories\ApplicationListRepository;
use App\Domain\LastUpdated\Repositories\LastUpdatedRepository;
use App\Infrastructure\Constants\UpdateDataTypes;

/**
 * Class UpdateApplicationList
 * @package App\Domain\LastUpdated\Actions
 */
class UpdateApplicationList
{
    /**
     * @var LastUpdatedRepository
     */
    protected LastUpdatedRepository $lastUpdatedRepository;
    /**
     * @var ApplicationListRepository
     */
    protected ApplicationListRepository $applicationListRepository;

    /**
     * UpdateApplicationList constructor.
     *
     * @param LastUpdatedRepository     $lastUpdatedRepository
     * @param ApplicationListRepository $applicationListRepository
     */
    public function __construct(LastUpdatedRepository $lastUpdatedRepository, ApplicationListRepository $applicationListRepository)
    {
        $this->lastUpdatedRepository     = $lastUpdatedRepository;
        $this->applicationListRepository = $applicationListRepository;
    }

    public function refreshViewAndUpdate(): void
    {
        $this->applicationListRepository->refreshView();

        $this->lastUpdatedRepository->updateOrCreate(
            [
                'updated_name' => UpdateDataTypes::APPLICATION_LIST,
            ],
        )->touch();
    }
}
