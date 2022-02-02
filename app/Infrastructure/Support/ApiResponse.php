<?php

namespace App\Infrastructure\Support;

/**
 * Trait ApiResponse
 * @package App\Infrastructure\Support
 */
trait ApiResponse
{
    /**
     * @param string     $message
     * @param array|null $data
     * @param array      $metadata
     *
     * @return array
     */
    public function makeResponse(string $message, $data, array $metadata = []): array
    {
        $response            = [];
        $response['success'] = true;

        if ( !is_null($data) ) {
            $response['data'] = $data;
        }

        if ( !empty($metadata) ) {
            $response['metadata'] = $metadata;
        }

        $response['message'] = $message;

        return $response;
    }

    /**
     * @param string $message
     * @param array  $data
     *
     * @return array
     */
    public function makeError(string $message, array $data = []): array
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];

        if ( !empty($data) ) {
            $res['data'] = $data;
        }

        return $res;
    }
}
