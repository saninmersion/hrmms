<?php


namespace App\Domain\Enumerators\Transformers;


use App\Domain\Enumerators\Models\Enumerator;
use League\Fractal\TransformerAbstract;

class EnumeratorApiTransformer extends TransformerAbstract
{
    public function transform(Enumerator $enumerator): array
    {
        return [
            'id'            => $enumerator->id,
            'name'          => $enumerator->name,
            'target'        => $enumerator->target,
            'supervisor_id' => $enumerator->supervisor_id,
        ];
    }
}
