<?php


namespace App\Domain\Users\Transformers;


use App\Domain\Users\Models\User;
use App\Infrastructure\Support\Helper;
use League\Fractal\TransformerAbstract;

/**
 * Class AuthUserProfileApiTransformer
 * @package App\Domain\Users\Transformers
 */
class AuthUserProfileApiTransformer extends TransformerAbstract
{
    public function transform(User $user): array
    {
        return [
            'id'                => $user->id,
            'name'              => $user->name,
            'email'             => $user->email,
            'username'          => $user->username,
            'display_name'      => $user->display_name,
            'metadata'          => null,
            'district_code'     => $user->district_code,
            'census_office_id'  => $user->census_office_id,
            'profile_photo_url' => $user->profile_photo_url,
            'mobile_number'     => $user->mobile_number ?? '',
        ];
    }
}
