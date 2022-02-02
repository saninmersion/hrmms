<?php

namespace App\Domain\AdminDivisions\Models;

use App\Infrastructure\Constants\DBTables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class District
 * @package App\Domain\AdminDivisions\Models
 *
 * @property int           $id
 * @property int|null      $province_code
 * @property int           $dist_id
 * @property int           $code
 * @property string        $title_en
 * @property string        $title_ne
 * @property bool          $is_old_district
 * @property Carbon        $created_at
 * @property Carbon        $updated_at
 *
 * @property Province|null $province
 * @property Collection    $municipalities
 */
class District extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = DBTables::DISTRICTS;

    /**
     * @var string[]
     */
    protected $fillable = [
        'province_code',
        'dist_id',
        'code',
        'title_en',
        'title_ne',
        'is_old_district',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'is_old_district' => 'bool',
    ];

    /**
     * @return BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    /**
     * @return HasMany
     */
    public function municipalities()
    {
        return $this->hasMany(Municipality::class, 'district_code', 'code');
    }
}
