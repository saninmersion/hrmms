<?php


namespace App\Domain\Enumerators\Repositories;


use App\Infrastructure\Support\Contracts\RepositoryInterface;

interface EnumeratorRepository extends RepositoryInterface
{
    public function bySupervisor(int $supervisorId): self;
}
