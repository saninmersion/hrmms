<?php

namespace App\Infrastructure\Support\Controller;

use App\Infrastructure\Support\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class Controller
 * @package App\Infrastructure\Support\Controller
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    use ApiResponse;

    /**
     * @var array
     */
    protected array $errors = [];

    /**
     * @var array
     */
    protected array $metadata = [];

    /**
     * @param array|null $result
     * @param string     $message
     * @param int        $code
     *
     * @return JsonResponse
     */
    public function sendResponse($result = [], $message = 'success', $code = Response::HTTP_OK)
    {
        if ( $result && Arr::has($result, 'meta') ) {
            $this->metadata = $result['meta'];
            unset($result['meta']);
        }

        return response()->json($this->makeResponse($message, $result, $this->metadata), $code);
    }

    /**
     * @param string $error
     * @param int    $code
     *
     * @return JsonResponse
     */
    public function sendError($error = "error", $code = Response::HTTP_NOT_FOUND)
    {
        return response()->json($this->makeError($error, $this->errors), $code);
    }

    /**
     * @param Collection $errors
     *
     * @return $this
     */
    public function setValidationErrors(Collection $errors): self
    {
        $this->errors = $errors->toArray();

        return $this;
    }

    /**
     * @param array $metadata
     *
     * @return $this
     */
    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }
}
