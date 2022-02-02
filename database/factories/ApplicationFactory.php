<?php

namespace Database\Factories;

use App\Domain\Applications\Models\Application;
use App\Infrastructure\Constants\StatusTypes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * Class ApplicationFactory
 * @package Database\Factories
 */
class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = $this->faker->randomElement(
            [StatusTypes::APPLICATION_DRAFT, StatusTypes::APPLICATION_SUBMITTED]
        );

        return [
            'for_supervisor' => $this->faker->boolean,
            'for_enumerator' => $this->faker->boolean,

            'status'           => $status,
            'application_date' => $status === StatusTypes::APPLICATION_SUBMITTED ? $this->faker->dateTimeBetween(
                '-60 days',
                'now'
            ) : null,
            'official'         => null,
        ];
    }

    /**j
     *
     * @param array $districts
     * @param array $municipalities
     *
     * @return ApplicationFactory
     */
    public function locations(array $districts, array $municipalities)
    {
        return $this->state(
            function () use ($districts, $municipalities) {
                return [
                    'locations' => [
                        'first'  => [
                            'district'     => $this->faker->randomElement($districts),
                            'municipality' => $this->faker->randomElement($municipalities),
                            'ward'         => $this->faker->numberBetween(1, 7),
                        ],
                        'second' => [
                            'district'     => $this->faker->randomElement($districts),
                            'municipality' => $this->faker->randomElement($municipalities),
                            'ward'         => $this->faker->numberBetween(1, 7),
                        ],
                    ],
                ];
            }
        );
    }

    protected function randomDistrictMunicipality(array $districts, Collection $municipalities): array
    {
        return ['district', 'municipality'];
    }
}
