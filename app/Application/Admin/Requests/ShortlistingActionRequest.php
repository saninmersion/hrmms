<?php

namespace App\Application\Admin\Requests;

use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Requests\WebFormRequest;

/**
 * Class ShortlistingActionRequest
 * @package App\Application\Admin\Requests
 */
class ShortlistingActionRequest extends WebFormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        $municipalityTable = DBTables::MUNICIPALITIES;
        $roles             = implode(',', [ApplicationType::SUPERVISOR, ApplicationType::ENUMERATOR]);

        return [
            'municipality' => "required|exists:{$municipalityTable},code",
            'role'         => "required|in:{$roles}",
        ];
    }
}
