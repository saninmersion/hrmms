<?php


namespace App\Application\Api\Requests;


use App\Domain\Enumerators\Presenters\EnumeratorDetailApiPresenter;
use App\Domain\Enumerators\Repositories\EnumeratorRepository;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Requests\ApiRequest;

class EnumeratorUpdateApiRequest extends ApiRequest
{
    protected EnumeratorRepository $enumeratorRepository;

    private array $data;

    public function __construct(EnumeratorRepository $enumeratorRepository)
    {
        $this->enumeratorRepository = $enumeratorRepository;
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
        $this->data = [
            'name'          => $this->input('name'),
            'target'        => (int) $this->input('target'),
        ];

        return $this;
    }

    /**
     * @param int $enumeratorId
     *
     * @return mixed
     */
    public function update(int $enumeratorId)
    {
        $this->enumeratorRepository->setPresenter(EnumeratorDetailApiPresenter::class);
        return $this->enumeratorRepository->update($this->data, $enumeratorId);
    }
}
