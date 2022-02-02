<?php

namespace App\Domain\Applications\Accessors;

use App\Domain\AdminDivisions\Models\District;
use App\Domain\AdminDivisions\Models\Municipality;

/**
 * Trait ApplicationAccessor
 * @package App\Domain\Applications\Accessors
 */
trait ApplicationAccessor
{
    /**
     * @return array
     */
    public function getApplicationLocationsTranslatedAttribute(): array
    {
        $locale = app()->getLocale();

        return collect($this->application_locations)->map(
            function ($location) use ($locale) {
                $tmpMuni     = Municipality::where('code', $location->local_government)->first();
                $tmpDistrict = District::where('code', $location->district)->first();

                $tmpLocation = [];

                $tmpLocation['district']         = null;
                $tmpLocation['local_government'] = null;
                $tmpLocation['ward']             = $location->ward;
                $tmpLocation['priority']         = $location->priority;

                if ( !is_null($tmpDistrict) ) {
                    $tmpLocation['district'] = $locale === 'en' ? $tmpDistrict->title_en : $tmpDistrict->title_ne;
                }
                if ( !is_null($tmpMuni) ) {
                    $tmpLocation['local_government'] = $locale === 'en' ? $tmpMuni->title_en : $tmpMuni->title_ne;
                }

                return $tmpLocation;
            }
        )->toArray();
    }
}
