<?php

namespace App\Application\Console\Commands;

use App\Domain\Applicants\Actions\AssignApplications;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\AuthRoles;
use Illuminate\Console\Command;
use Mockery\Exception;

/**
 * Class AssignApplicationsToVerifiers
 * @package App\Application\Console\Commands
 */
class AssignApplicationsToVerifiers extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cbs:applications:assign-to-verifiers {verifier}';

    /**
     * @var string
     */
    protected $description = 'Assign applications to verifiers.';

    /**
     * @param AssignApplications $assignApplications
     *
     * @return void
     */
    public function handle(AssignApplications $assignApplications, UserRepository $userRepository)
    {
        $verifierId = $this->argument('verifier');
        try {
            /** @var User $verifier */
            $verifier = $userRepository->find($verifierId);

            if ( $verifier->role !== AuthRoles::VERIFIERS ) {
                throw new Exception();
            }

            $assignApplications->assignApplicationsToVerifiers($verifier, config('config.number-of-assignments'));

            $this->info(config('config.number-of-assignments') . " applications were assigned to $verifier->name.");
        } catch (\Exception $exception) {
            $this->error('Verifier not found');
        } catch (\Throwable $e) {
            $this->error('Verifier not found');
        }
    }
}
