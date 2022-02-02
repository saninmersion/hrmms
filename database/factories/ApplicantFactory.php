<?php

namespace Database\Factories;

use App\Domain\Applicants\Models\Applicant;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\Ethnicities;
use App\Infrastructure\Constants\General;
use App\Infrastructure\Constants\MotherTongues;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class ApplicantFactory
 * @package Database\Factories
 */
class ApplicantFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Applicant::class;

    /**
     * @return array
     */
    public function definition()
    {
        return [
            'created_at'                 => $this->faker->dateTimeBetween(
                '-60 days',
                'now'
            ),
            'citizenship_number'         => substr($this->faker->uuid, 0, rand(10, 15)),
            // @todo convert date to bikramsambat
            'citizenship_issued_date_bs' => $this->faker->date('Y-m-d'),
            //            'citizenship_issued_district_code' => $this->faker->randomElement($districts),
            // @todo convert date to bikramsambat
            'dob_bs'                     => $this->faker->date('Y-m-d'),
            'mobile_number'              => $this->faker->phoneNumber,
            'details'                    => [
                'dob_ad'            => $this->faker->date('Y-m-d'),
                'gender'            => $this->faker->randomElement(General::genderOptions()),
                'ethnicity'         => $this->faker->randomElement(Ethnicities::ethnicities()),
                "name_in_nepali"    => [
                    "first_name"  => $this->faker->firstName,
                    "middle_name" => $this->faker->word,
                    "last_name"   => $this->faker->lastName,
                ],
                "name_in_english"   => [
                    "first_name"  => $this->faker->firstName,
                    "middle_name" => $this->faker->word,
                    "last_name"   => $this->faker->lastName,
                ],
                'mother_tongue'     => $this->faker->randomElement(MotherTongues::motherTongues()),
                'disability'        => $this->faker->randomElement(ApplicationConstants::disabilities()),
                "mother_name"       => [
                    "first_name"  => $this->faker->firstName,
                    "middle_name" => $this->faker->word,
                    "last_name"   => $this->faker->lastName,
                ],
                "father_name"       => [
                    "first_name"  => $this->faker->firstName,
                    "middle_name" => $this->faker->word,
                    "last_name"   => $this->faker->lastName,
                ],
                "grand_father_name" => [
                    "first_name"  => $this->faker->firstName,
                    "middle_name" => $this->faker->word,
                    "last_name"   => $this->faker->lastName,
                ],
                'qualification'     => [
                    'training'                    => [
                        'period'    => $this->faker->numberBetween(0, 100),
                        'institute' => $this->faker->company,
                    ],
                    'education'                   => [
                        'extra'      => [
                            'major_subject' => $this->faker->randomElement(ApplicationConstants::majorSubjects()),
                        ],
                        'enumerator' => [
                            'grade'          => '',
                            'percentage'     => $this->faker->randomFloat(1, 0, 100),
                            'major_subject'  => $this->faker->randomElement(ApplicationConstants::majorSubjects()),
                            'grading_system' => $this->faker->randomElement(ApplicationConstants::gradingSystems()),
                        ],
                        'supervisor' => [
                            'grade'          => '',
                            'percentage'     => $this->faker->randomFloat(1, 0, 100),
                            'major_subject'  => $this->faker->randomElement(ApplicationConstants::majorSubjects()),
                            'grading_system' => $this->faker->randomElement(ApplicationConstants::gradingSystems()),
                        ],
                    ],
                    'experience'                  => [
                        'period_day'   => $this->faker->numberBetween(0, 50),
                        'organization' => $this->faker->company,
                        'period_month' => $this->faker->numberBetween(0, 24),
                    ],
                    'has_training'                => $this->faker->boolean,
                    'has_experience'              => $this->faker->boolean,
                    'has_education_qualification' => $this->faker->boolean,
                ],
            ],
        ];
    }

    /**
     * @param array $districts
     * @param array $municipalities
     *
     * @return ApplicantFactory
     */
    public function locations(array $districts, array $municipalities)
    {
        return $this->state(
            function () use ($districts, $municipalities) {
                return [
                    'citizenship_issued_district_code' => $this->faker->randomElement($districts),
                    'permanent_address'                => [
                        'district'     => $this->faker->randomElement($districts),
                        'municipality' => $this->faker->randomElement($municipalities),
                        'ward'         => $this->faker->numberBetween(1, 7),
                    ],
                    'current_address'                  => [
                        'district'     => $this->faker->randomElement($districts),
                        'municipality' => $this->faker->randomElement($municipalities),
                        'ward'         => $this->faker->numberBetween(1, 7),
                    ],
                ];
            }
        );
    }
}
