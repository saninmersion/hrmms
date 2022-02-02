<?php

namespace App\Domain\Medias\Models;

use App\Domain\Applicants\Models\Applicant;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Media
 * @package App
 *
 * @property int    $id
 * @property string $filename
 * @property string $path
 * @property string $field_name
 * @property object $metadata
 *
 * @property string $url
 */
class Media extends Model
{
    /**
     * @var string
     */
    protected $table = DBTables::SYS_MEDIAS;

    /**
     * @var string[]
     */
    protected $appends = ['url'];

    /**
     * @var string[]
     */
    protected $casts = [
        'metadata' => 'object',
    ];

    /**
     * @var string[]
     */
    protected $fillable = ['filename', 'path', 'field_name', 'metadata'];

    /**
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return $this->path ? Helper::fileUrl($this->path) : '';

    }

    /**
     * @return MorphTo
     */
    public function applicant()
    {
        $this->morphedByMany(Applicant::class, 'mediaable', DBTables::SYS_MEDIAABLE);

        return $this->morphTo();
    }
}
