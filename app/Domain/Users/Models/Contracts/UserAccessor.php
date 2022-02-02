<?php

namespace App\Domain\Users\Models\Contracts;

/**
 * Trait UserAccessor
 * @package App\Domain\Users\Models\Contracts
 */
trait UserAccessor
{
    /**
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        if ( $this->name ) {
            return $this->name;
        }

        if ( $this->username ) {
            return $this->username;
        }

        return $this->email;
    }
}
