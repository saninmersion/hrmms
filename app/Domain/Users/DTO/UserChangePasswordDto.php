<?php

namespace App\Domain\Users\DTO;

use App\Infrastructure\Support\DTO\AbstractDto;
use App\Infrastructure\Support\DTO\DtoInterface;
use Illuminate\Support\Arr;

/**
 * Class UserChangePasswordDto
 * @package App\Domain\Users\DTO
 */
class UserChangePasswordDto extends AbstractDto implements DtoInterface
{
    /**
     * @var string
     */
    public string $password;

    /**
     * @param array $data
     */
    protected function map(array $data): void
    {
        $this->password = Arr::get($data, 'password');
    }

    /**
     * @return string[]
     */
    protected function validationRules(): array
    {
        return [
            'password' => 'required',
        ];
    }
}
