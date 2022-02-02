<?php


namespace App\Domain\Users\Presenters;


use App\Domain\Users\Transformers\AuthUserProfileApiTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AuthUserProfileApiPresenter
 * @package App\Domain\Users\Presenters
 */
class AuthUserProfileApiPresenter extends FractalPresenter
{
    public function getTransformer(): AuthUserProfileApiTransformer
    {
        return new AuthUserProfileApiTransformer();
    }
}
