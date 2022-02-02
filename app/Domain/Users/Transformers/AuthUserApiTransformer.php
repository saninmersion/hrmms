<?php

namespace App\Domain\Users\Transformers;

use App\Domain\AdminDivisions\Transformers\AdminDistrictTransformer;
use App\Domain\CensusOffices\Transformers\CensusOfficeTransformer;
use App\Domain\Users\Models\User;
use App\Infrastructure\Support\Helper;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

/**
 * Class AuthUserApiTransformer
 * @package App\Domain\Users\Transformers
 */
class AuthUserApiTransformer extends TransformerAbstract
{
    /**
     * @var string[]
     */
    protected $defaultIncludes = ['district', 'censusOffice'];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user): array
    {
        return [
            'id'               => $user->id,
            'name'             => $user->name,
            'email'            => $user->email,
            'username'         => $user->username,
            'display_name'     => $user->display_name,
            'profile_picture'  => [
                'path' => $user->profile_photo_path,
                'url'  => $user->profile_photo_url,
            ],
            'role'             => $user->role,
            'status'           => $user->status ?? '',
            'district_code'    => $user->district_code,
            'census_office_id' => $user->census_office_id,
            'created_at'       => Helper::dateResponse($user->created_at),
        ];
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeDistrict(User $user): ?Item
    {
        if ( !$user->district ) {
            return null;
        }

        return $this->item($user->district, new AdminDistrictTransformer());
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeCensusOffice(User $user): ?Item
    {
        if ( !$user->census_office_id ) {
            return null;
        }

        return $this->item($user->censusOffice, new CensusOfficeTransformer());
    }
}
