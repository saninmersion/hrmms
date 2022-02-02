<?php

namespace App\Domain\Users\DTO;

use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Support\DTO\AbstractDto;
use App\Infrastructure\Support\DTO\DtoInterface;
use Illuminate\Support\Arr;

/**
 * Class UserCreateDto
 * @package App\Domain\Users\DTO
 */
class UserCreateDto extends AbstractDto implements DtoInterface
{
    /**
     * @var string
     */
    public string $name;
    /**
     * @var string
     */
    public string $email;
    /**
     * @var string
     */
    public string $username;
    /**
     * @var string
     */
    public string $password;
    /**
     * @var string
     */
    public string $role;
    /**
     * @var string|null
     */
    public ?string $district = null;

    /**
     * @var string|null
     */
    public ?string $status = null;

    /**
     * @var int|null
     */
    public ?int $censusOffice = null;

    /**
     * @param array $data
     */
    protected function map(array $data): void
    {
        $this->name     = Arr::get($data, 'name');
        $this->email    = Arr::get($data, 'email');
        $this->username = Arr::get($data, 'username');
        $this->password = Arr::get($data, 'password');
        $this->role     = Arr::get($data, 'role');

        if ( $this->role === AuthRoles::DISTRICT_STAFFS || $this->role === AuthRoles::SUPERVISOR ) {
            $this->district = Arr::get($data, 'district');
        }

        if ( $this->role === AuthRoles::SUPERVISOR ) {
            $this->censusOffice = Arr::get($data, 'census_office');
        }
    }

    /**
     * @return string[]
     */
    protected function validationRules(): array
    {
        return [
            'name'          => 'required',
            'email'         => 'required',
            'username'      => 'required',
            'password'      => 'required',
            'role'          => 'required',
            'district'      => 'nullable',
            'census_office' => 'nullable',
            'status'        => 'nullable',
        ];
    }
}
