<?php

namespace App\Domain\Applicants\Models;

use App\Infrastructure\Constants\DBTables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Applicant
 * @package App\Domain\Applicants\Models
 *
 * @property int         $id
 * @property string      $filename
 * @property string      $path
 * @property Carbon|null $exported_at
 * @property object|null $metadata
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 *
 * @property string      $url
 * @property Carbon|null $parsed_as_of_date
 */
class ApplicantExport extends Model
{
    /**
     * @var string
     */
    protected $table = DBTables::APPLICANT_EXPORTS;

    /**
     * @var string[]
     */
    protected $fillable = [
        'filename',
        'path',
        'exported_at',
        'metadata',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'exported_at',
    ];

    protected $casts = [
        'metadata' => 'object',
    ];

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->path ? route('admin.applications.exports.download', [$this->id, $this->path]) : '#';
    }

    /**
     * @return Carbon|null
     */
    public function getParsedAsOfDateAttribute(): ?Carbon
    {
        $asOf = $this->metadata->data_as_of ?? '';
        if ( !$asOf ) {
            return null;
        }

        return Carbon::parse($asOf);
    }
}
