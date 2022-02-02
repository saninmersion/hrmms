<?php


namespace App\Application\Api\Requests;


use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Requests\ApiRequest;
use App\Infrastructure\Support\Rules\MobileNumber;

class UserProfileUpdateRequest extends ApiRequest
{
    private array $data;
    /**
     * @var \App\Domain\Users\Repositories\UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name'          => trans('admin-users.full-name'),
            'mobile_number' => trans('admin-users.mobile-number'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        /** @var User $user */
        $user = AuthHelper::currentUser(Guard::API);

        return [
            'name'          => "required|string|max:200",
            'mobile_number' => [
                'nullable',
                new MobileNumber(),
                //check mobile number is unique
                function ($attribute, $value, $fail) use ($user) {
                    $model = $this->userRepository->getBuilder();
                    $user  = $model->where('mobile_number', $value)->where('id', '!=', $user->id)->first();
                    if ( $user ) {
                        $fail(trans('validation.unique', [$attribute => trans('admin-users.mobile-number')]));
                    }
                },
            ],
        ];
    }

    public function prepareData(): UserProfileUpdateRequest
    {
        $this->data = [
            'name'          => $this->input('name'),
            'mobile_number' => $this->input('mobile_number'),
        ];

        return $this;
    }

    /**
     * @param int $userId
     *
     * @return mixed
     */
    public function update(int $userId)
    {
        $this->userRepository->with('enumerators');

        return $this->userRepository->update($this->data, $userId);
    }
}
