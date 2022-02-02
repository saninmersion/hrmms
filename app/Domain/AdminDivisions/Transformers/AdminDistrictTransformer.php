<?php

namespace App\Domain\AdminDivisions\Transformers;

use App\Domain\AdminDivisions\Models\District;
use League\Fractal\TransformerAbstract;

/**
 * Class AdminDistrictTransformer
 * @package App\Domain\AdminDivisions\Transformers
 */
class AdminDistrictTransformer extends TransformerAbstract
{
    /**
     * @param District $district
     *
     * @return array
     */
    public function transform(District $district): array
    {
        return [
            'code'     => $district->code,
            'title_en' => $district->title_en,
            'title_ne' => $district->title_ne,
        ];
    }
}
