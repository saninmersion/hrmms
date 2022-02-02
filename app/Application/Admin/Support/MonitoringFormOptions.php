<?php

namespace App\Application\Admin\Support;

use App\Infrastructure\Constants\General;
use App\Infrastructure\Constants\MonitoringFormConstants;

class MonitoringFormOptions
{
    /**
     * @return array
     */
    public static function enumeratorFormOptions(): array
    {
        return [
            'explainedOptions'     => MonitoringFormConstants::explainedOptions(),
            'remainedOptions'      => MonitoringFormConstants::remainedOptions(),
            'accurateOptions'      => MonitoringFormConstants::accurateOptions(),
            'yesNoOptions'         => MonitoringFormConstants::yesNoOptions(),
            'genderOptions'        => General::genderOptions(),
            'wereOptions'          => MonitoringFormConstants::wereOptions(),
            'didOptions'           => MonitoringFormConstants::didOptions(),
            'doneOptions'          => MonitoringFormConstants::doneOptions(),
            'homeOwnershipOptions' => MonitoringFormConstants::homeOwnerShipOptions(),
            'roofOptions'          => MonitoringFormConstants::roofOptions(),
        ];
    }

    /**
     * @return array
     */
    public static function overallFormOptions(): array
    {
        return [
            'writtenOptions'  => MonitoringFormConstants::writtenOptions(),
            'informedOptions' => MonitoringFormConstants::informedOptions(),
            'missedOptions'   => MonitoringFormConstants::missedOptions(),
            'hasOptions'      => MonitoringFormConstants::hasOptions(),
        ];
    }

    public static function supervisorFormOptions(): array
    {
        return [
            'explainedOptions' => MonitoringFormConstants::explainedOptions(),
            'remainedOptions'  => MonitoringFormConstants::remainedOptions(),
            'accurateOptions'  => MonitoringFormConstants::accurateOptions(),
            'genderOptions'    => General::genderOptions(),
            'wereOptions'      => MonitoringFormConstants::wereOptions(),
            'didOptions'       => MonitoringFormConstants::didOptions(),
            'doneOptions'      => MonitoringFormConstants::doneOptions(),
        ];
    }
}
