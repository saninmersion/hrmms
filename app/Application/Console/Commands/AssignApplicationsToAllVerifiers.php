<?php

namespace App\Application\Console\Commands;

use App\Domain\Applicants\Actions\AssignApplications;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\AuthRoles;
use Illuminate\Console\Command;

/**
 * Class AssignApplicationsToAllVerifiers
 * @package App\Application\Console\Commands
 */
class AssignApplicationsToAllVerifiers extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:applications:assign-to-all';

    /**
     * @var string
     */
    protected $description = 'Assign applications to all verifiers.';

    /**
     * @param AssignApplications $assignApplications
     *
     * @return void
     */
    public function handle(AssignApplications $assignApplications, UserRepository $userRepository)
    {
        $verifiers = $userRepository->byRole(AuthRoles::VERIFIERS)->all();
        try {
            $verifiers->each(
                function ($verifier) use ($assignApplications) {
                    $assignApplications->assignApplicationsToVerifiers(
                        $verifier,
                        (int) config('config.number-of-assignments')
                    );
                }
            );

            $this->info('Applications were assigned.');
        } catch (\Exception $exception) {
            $this->error('Error during assignations');
        } catch (\Throwable $e) {
            $this->error('Error during assignations');
        }
    }
}
