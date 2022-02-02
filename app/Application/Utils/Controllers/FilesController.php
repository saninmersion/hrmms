<?php

namespace App\Application\Utils\Controllers;

use App\Infrastructure\Support\Controller\FrontController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class FilesController
 * @package App\Application\Utils\Controllers
 */
class FilesController extends FrontController
{
    /**
     * @param string $disk
     * @param string $path
     *
     * @return StreamedResponse|void
     */
    public function __invoke(string $disk, string $path)
    {
        $pathInfo = pathinfo($path);
        try {
            if ( !in_array(($pathInfo['extension'] ?? ''), ['png', 'jpg', 'jpeg']) ) {
                return Storage::disk($disk)->response($path);
            }

            $server = ServerFactory::create(
                [
                    'response'       => new LaravelResponseFactory(app('request')),
                    'source'         => Storage::disk($disk)->getDriver(),
                    'cache'          => Storage::disk('glide-cache')->getDriver(),
                    'max_image_size' => 1024 * 1024,
                ]
            );
            $server->setGroupCacheInFolders(true);
            $server->setDefaults(
                [
                    'fit' => 'max',
                ]
            );

            $options   = request()->only('w', 'h');
            $validator = Validator::make(
                $options,
                [
                    'w' => 'nullable|integer|min:0|max:1024',
                    'h' => 'nullable|integer|min:0|max:1024',
                ]
            );

            if ( $validator->fails() ) {
                $options = [];
            }

            return $server->getImageResponse($path, $options);
        } catch (\Exception $exception) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
