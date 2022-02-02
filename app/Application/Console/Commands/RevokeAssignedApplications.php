<?php

namespace App\Application\Console\Commands;

use App\Domain\Applicants\Repositories\VerifierAssignmentRepository;
use Illuminate\Console\Command;

/**
 * Class RevokeAssignedApplications
 * @package App\Application\Console\Commands
 */
class RevokeAssignedApplications extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:applications:revoke';

    /**
     * @var string
     */
    protected $description = 'Revoke Assigned applications that have not been verified.';

    /**
     * @param VerifierAssignmentRepository $assignmentRepository
     *
     * @return void
     */
    public function handle(VerifierAssignmentRepository $assignmentRepository)
    {
        $assignedApplications = $assignmentRepository->doesntHave('verification')->get(['id']);
        try {
            $assignedApplications->each(
                function ($application) {
                    $application->delete();
                }
            );

            $this->info('Applications were revoked.');
        } catch (\Exception $exception) {
            $this->error('Error during revoking applications');
        } catch (\Throwable $e) {
            $this->error('Error during revoking applications');
        }
    }
}
