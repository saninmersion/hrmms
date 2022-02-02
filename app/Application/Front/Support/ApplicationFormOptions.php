<?php

namespace App\Application\Front\Support;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\Ethnicities;
use App\Infrastructure\Constants\MotherTongues;

/**
 * Class ApplicationFormOptions
 * @package App\Application\Front\Support
 */
class ApplicationFormOptions
{
    /**
     * @return array
     */
    public static function formOptions(): array
    {
        $districtRepository = app(DistrictRepository::class);

        return [
            'newDistricts'     => $districtRepository->allDistrictsWithMunicipalitiesOptions()->values()->toArray(),
            'oldDistricts'     => $districtRepository->allDistrictForOptions()->values()->toArray(),
            'applicationTypes' => ApplicationType::all(),
            'gradingSystems'   => ApplicationConstants::gradingSystems(),
            'majorSubjects'    => ApplicationConstants::majorSubjects(),
            'ethnicities'      => Ethnicities::ethnicities(),
            'motherTongues'    => MotherTongues::motherTongues(),
            'disabilities'     => ApplicationConstants::disabilities(),
        ];
    }
}
