<?php

namespace App\Application\Admin\Support;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\StatusTypes;

/**
 * Class ApplicantFilterOptions
 * @package App\Application\Admin\Support
 */
class ApplicantFilterOptions
{
    /**
     * @return array
     */
    public static function options(): array
    {
        $districtRepository = app(DistrictRepository::class);

        return [
            'districts'          => $districtRepository->allDistrictForOptions()->values()->toArray(),
            'genders'            => ApplicationConstants::genders(),
            'applicationTypes'   => ApplicationType::all(),
            'applicationStatues' => StatusTypes::all(),
        ];
    }
}
