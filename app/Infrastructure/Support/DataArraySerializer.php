<?php

namespace App\Infrastructure\Support;

use League\Fractal\Serializer\ArraySerializer;

/**
 * Class DataArraySerializer
 * @package App\Infrastructure\Support
 */
class DataArraySerializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     * @SuppressWarnings("unused")
     */
    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    public function collection($resourceKey, array $data)
    {
        return $data;
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     * @SuppressWarnings("unused")
     */
    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    public function item($resourceKey, array $data)
    {
        return $data;
    }

    /**
     * Serialize null resource.
     *
     * @return array|null
     */
    public function null()
    {
        return null;
    }
}
