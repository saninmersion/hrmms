<?php

namespace App\Domain\Users\Presenters;

use App\Domain\Users\Transformers\AuthUserApiTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AuthUserApiPresenter
 * @package App\Domain\Users\Presenters
 */
class AuthUserApiPresenter extends FractalPresenter
{
    /**
     * @return AuthUserApiTransformer
     */
    public function getTransformer()
    {
        return new AuthUserApiTransformer();
    }
}
