<?php


namespace App\Domain\Enumerators\Requests;


use App\Domain\Enumerators\Repositories\EnumeratorRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Requests\WebFormRequest;

class EnumeratorRequest extends WebFormRequest
{
    /**
     * @var \App\Domain\Enumerators\Repositories\EnumeratorRepository
     */
    private EnumeratorRepository $enumeratorRepository;

    private array $data;

    public function __construct(EnumeratorRepository $enumeratorRepository)
    {
        $this->enumeratorRepository = $enumeratorRepository;
    }

    public function rules(): array
    {
        return [
            'name'   => 'required',
            'target' => 'required',
        ];
    }

    public function setForm(): self
    {
        /** @var User $user */
        $user       = AuthHelper::currentUser();
        $this->data = [
            'name'          => $this->input('name'),
            'target'        => $this->input('target'),
            'supervisor_id' => $user->id,
        ];

        return $this;
    }

    public function setFormForUpdate(): self
    {
        $this->data = [
            'target'        => $this->input('target'),
        ];

        return $this;
    }

    /**
     * @return mixed
     */
    public function store()
    {
        return $this->enumeratorRepository->create($this->data);
    }

    /**
     * @return mixed
     */
    public function update(int $enumeratorId)
    {
        return $this->enumeratorRepository->update($this->data, $enumeratorId);
    }
}
