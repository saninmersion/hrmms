<?php

namespace App\Domain\AdminDivisions\Models;

use App\Domain\Applicants\Models\ShortListedApplicant;
use App\Infrastructure\Constants\DBTables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class Municipality
 * @package App\Domain\AdminDivisions\Models
 * @property int      $id
 * @property int      $province_code
 * @property int      $district_code
 * @property int      $mun_id
 * @property int      $code
 * @property string   $title_en
 * @property string   $title_ne
 * @property int      $total_wards
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 *
 * @property Province $province
 * @property District $district
 * @property Collection $shortlists
 */
class Municipality extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = DBTables::MUNICIPALITIES;

    /**
     * @var string[]
     */
    protected $fillable = [
        'province_code',
        'district_code',
        'mun_id',
        'code',
        'title_en',
        'title_ne',
        'total_wards',
    ];

    /**
     * @return BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    /**
     * @return BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

    /**
     * @return HasMany
     */
    public function shortlists(): HasMany
    {
        return $this->hasMany(ShortListedApplicant::class, 'municipality_code', 'code');
    }
}
