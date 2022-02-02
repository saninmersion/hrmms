<?php

namespace Database\Factories;

use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Class UserFactory
 * @package Database\Factories
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $roles = AuthRoles::assignable();

        return [
            'role'               => $this->faker->randomElement($roles),
            'name'               => $this->faker->name,
            'email'              => $this->faker->unique()->safeEmail,
            'email_verified_at'  => now(),
            'username'           => $this->faker->unique()->userName,
            'password'           => 'secret',
            'remember_token'     => Str::random(10),
            'profile_photo_path' => null,
            'status'             => UserStatus::STATUS_ACTIVE,
        ];
    }

    /**
     * @return Factory
     */
    public function superAdmin()
    {
        return $this->state(
            function (array $attributes) {
                return [
                    'role'              => AuthRoles::SUPER_ADMIN,
                    'name'              => 'Administrator',
                    'username'          => 'superadmin',
                    'email'             => 'admin@admin.com',
                    'email_verified_at' => now(),
                    'password'          => 'password',
                ];
            }
        );
    }

    /**
     * @return Factory
     */
    public function districtStaff()
    {
        return $this->state(
            function (array $attributes) {
                return [
                    'role'              => AuthRoles::DISTRICT_STAFFS,
                    'name'              => 'District',
                    'username'          => 'district',
                    'email'             => 'admin@district.com',
                    'email_verified_at' => now(),
                    'password'          => 'password',
                ];
            }
        );
    }

    /** @return Factory */
    public function censusOfficeMonitor()
    {
        return $this->state(
            function (array $attributes) {
                return [
                    'role'              => AuthRoles::MONITORING,
                    'name'              => 'District',
                    'username'          => 'district',
                    'email'             => 'admin@district.com',
                    'email_verified_at' => now(),
                    'password'          => 'password',
                ];
            }
        );
    }
}
