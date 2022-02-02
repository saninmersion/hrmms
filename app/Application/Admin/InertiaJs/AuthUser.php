<?php

namespace App\Application\Admin\InertiaJs;

use App\Domain\Users\Transformers\AuthUserTransformer;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Contracts\InertiaDataSharable;
use App\Infrastructure\Support\DataArraySerializer;
use Closure;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;

/**
 * Class AuthUser
 * @package App\Application\Admin\InertiaJs
 */
class AuthUser implements InertiaDataSharable
{
    /**
     * @return Closure
     */
    public function data()
    {
        return function () {
            $user = AuthHelper::currentUser();

            if ( !$user ) {
                return null;
            }

            $transformed = new Item($user, new AuthUserTransformer());

            return (new Manager())->setSerializer(new DataArraySerializer())->createData($transformed)->toArray();
        };
    }

    /**
     * @return string
     */
    public function key(): string
    {
        return 'auth.user';
    }
}
