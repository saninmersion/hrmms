<?php

namespace App\Domain\CensusOffices\Transformers;

use App\Domain\CensusOffices\Models\CensusOffice;
use League\Fractal\TransformerAbstract;

/**
 * Class CensusOfficeTransformer
 * @package App\Domain\AdminDivisions\Transformers
 */
class CensusOfficeTransformer extends TransformerAbstract
{
    /**
     * @param CensusOffice $item
     *
     * @return array
     */
    public function transform(CensusOffice $item): array
    {
        return [
            'id'          => $item->id,
            'office_name' => $item->office_name,
        ];
    }
}
