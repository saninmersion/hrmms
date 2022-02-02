<?php

namespace App\Domain\Applications\Actions;

use App\Domain\Applicants\Models\Applicant;
use App\Domain\Applications\Repositories\ApplicationRepository;
use Exception;
use Illuminate\Database\DatabaseManager;
use Throwable;

/**
 * Class SaveApplication
 * @package App\Domain\Applications\Actions
 */
class SaveApplication
{
    /**
     * @var ApplicationRepository
     */
    protected ApplicationRepository $applicationRepository;
    /**
     * @var DatabaseManager
     */
    protected DatabaseManager $databaseManager;

    /**
     * SaveApplication constructor.
     *
     * @param ApplicationRepository $applicationRepository
     * @param DatabaseManager       $databaseManager
     */
    public function __construct(ApplicationRepository $applicationRepository, DatabaseManager $databaseManager)
    {
        $this->applicationRepository = $applicationRepository;
        $this->databaseManager       = $databaseManager;
    }

    /**
     * @param Applicant $applicant
     * @param array     $application
     *
     * @throws Throwable
     */
    public function save(Applicant $applicant, array $application): void
    {
        $this->databaseManager->beginTransaction();

        try {
            $applicant->save();

            $applicant->application()->updateOrCreate(['applicant_id' => $applicant->id], $application);
        } catch (Exception $exception) {
            $this->databaseManager->rollBack();

            throw $exception;
        }

        $this->databaseManager->commit();
    }
}
