<?php

namespace App\Domain\Medias\Transformers;

use App\Domain\Medias\Models\Media;
use League\Fractal\TransformerAbstract;

/**
 * Class MediaTransformer
 * @package App\Domain\Medias\Transformers
 */
class MediaTransformer extends TransformerAbstract
{
    /**
     * @param Media $media
     *
     * @return array
     */
    public function transform(Media $media): array
    {
        return [
            'id'       => $media->id,
            'filename' => $media->filename,
            'path'     => $media->path,
            'url'      => $media->url,
        ];
    }
}
