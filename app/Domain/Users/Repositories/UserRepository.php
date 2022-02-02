<?php

namespace App\Domain\Users\Repositories;

use App\Infrastructure\Support\Contracts\RepositoryInterface;

/**
 * Interface UserRepository
 * @package App\Domain\Users\Repositories
 */
interface UserRepository extends RepositoryInterface
{
    /**
     * @param string $roleName
     *
     * @return $this
     */
    public function byRole(string $roleName): self;

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function exceptRoles(array $roles): self;

    /**
     * @param string $status
     *
     * @return $this
     */
    public function byStatus(string $status): self;

    /**
     * @param array $status
     *
     * @return $this
     */
    public function exceptStatus(array $status): self;
}
