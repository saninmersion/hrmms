<?php

namespace App\Application\Utils\Controllers;

use App\Application\Front\Support\Exceptions\FormNonEditableException;
use App\Domain\Medias\Presenters\MediaPresenter;
use App\Domain\Medias\Repositories\MediaRepository;
use App\Infrastructure\Constants\General;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\FrontController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class MediaController
 * @package App\Application\Utils\Controllers
 */
class MediaController extends FrontController
{
    /**
     * @param Request         $request
     * @param MediaRepository $mediaRepository
     *
     * @return JsonResponse
     * @throws FormNonEditableException
     */
    public function frontUpload(Request $request, MediaRepository $mediaRepository): JsonResponse
    {
        AuthHelper::guardEditableForm();

        $media = null;
        try {
            if ( $request->hasFile('media') ) {
                $validator = Validator::make($request->all(), ['media' => 'image|max:600']);
                if ( $validator->fails() ) {
                    throw new ValidationException($validator);
                }

                /** @var UploadedFile $file */
                $file = $request->file('media');
                $path = $file->store(General::PATH_APPLICATION);

                $mediaRepository->setPresenter(MediaPresenter::class);
                $media = $mediaRepository->create(
                    [
                        'filename'   => $file->getClientOriginalName(),
                        'path'       => $path,
                        'field_name' => $request->input('field_name', null),
                        'metadata'   => [
                            'extension' => $file->extension(),
                            'size'      => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                        ],
                    ]
                );

            }
        } catch (ValidationException $exception) {
            return $this->sendError(data_get($exception->errors(), 'media')[0], 422);
        } catch (Exception $exception) {
            logger()->error($exception);

            return $this->sendError('Failed to upload file.');
        }

        return $this->sendResponse($media, 'Uploaded', Response::HTTP_CREATED);

    }

    /**
     * @param Request         $request
     * @param MediaRepository $mediaRepository
     *
     * @return JsonResponse
     * @throws FormNonEditableException
     */
    public function adminUpload(Request $request, MediaRepository $mediaRepository): JsonResponse
    {
        $media = null;
        try {
            if ( $request->hasFile('media') ) {
                $validator = Validator::make($request->all(), ['media' => 'image|max:600']);
                if ( $validator->fails() ) {
                    throw new ValidationException($validator);
                }

                /** @var UploadedFile $file */
                $file = $request->file('media');
                $path = $file->store(General::PATH_APPLICATION);

                $mediaRepository->setPresenter(MediaPresenter::class);
                $media = $mediaRepository->create(
                    [
                        'filename'   => $file->getClientOriginalName(),
                        'path'       => $path,
                        'field_name' => $request->input('field_name', null),
                        'metadata'   => [
                            'extension' => $file->extension(),
                            'size'      => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                        ],
                    ]
                );

            }
        } catch (ValidationException $exception) {
            return $this->sendError(data_get($exception->errors(), 'media')[0], 422);
        } catch (Exception $exception) {
            logger()->error($exception);

            return $this->sendError('Failed to upload file.');
        }

        return $this->sendResponse($media, 'Uploaded', Response::HTTP_CREATED);

    }
}
