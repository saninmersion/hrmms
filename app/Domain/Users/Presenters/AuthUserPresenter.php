<?php

namespace App\Domain\Users\Presenters;

use App\Domain\Users\Transformers\AuthUserTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AuthUserPresenter
 * @package App\Domain\Users\Presenters
 */
class AuthUserPresenter extends FractalPresenter
{
    /**
     * @return AuthUserTransformer
     */
    public function getTransformer()
    {
        return new AuthUserTransformer();
    }
}
