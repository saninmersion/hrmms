<?php


namespace App\Application\Api\Requests;


use App\Domain\Enumerators\Presenters\EnumeratorDetailApiPresenter;
use App\Domain\Enumerators\Repositories\EnumeratorRepository;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Requests\ApiRequest;

class EnumeratorCreateApiRequest extends ApiRequest
{
    protected EnumeratorRepository $enumeratorRepository;

    private array $data;

    public function __construct()
    {
        $this->enumeratorRepository = app()->make(EnumeratorRepository::class);
    }

    public function rules(): array
    {
        return [
            'name'   => 'required',
            'target' => 'required|numeric',
        ];
    }

    public function prepareData(): self
    {
        /** @var \App\Domain\Users\Models\User $user */
        $user       = AuthHelper::currentUser(Guard::API);
        $this->data = [
            'name'          => $this->input('name'),
            'target'        => (int) $this->input('target'),
            'supervisor_id' => $user->id,
        ];

        return $this;
    }

    /**
     * @return mixed
     */
    public function store()
    {
        $this->enumeratorRepository->setPresenter(EnumeratorDetailApiPresenter::class);
        return $this->enumeratorRepository->create($this->data);
    }
}
