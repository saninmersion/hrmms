<?php

namespace App\Domain\AdminDivisions\Models;

use App\Infrastructure\Constants\DBTables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class Province
 * @package App\Domain\AdminDivisions\Models
 *
 * @property int        $id
 * @property int        $code
 * @property string     $title_en
 * @property string     $title_ne
 * @property Carbon     $created_at
 * @property Carbon     $updated_at
 *
 * @property Collection $districts
 * @property Collection $municipalities
 */
class Province extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = DBTables::PROVINCES;

    /**
     * @var string[]
     */
    protected $fillable = ['code', 'title_en', 'title_ne'];

    /**
     * @return HasMany
     */
    public function districts()
    {
        return $this->hasMany(District::class, 'province_code', 'code');
    }

    /**
     * @return HasMany
     */
    public function municipalities()
    {
        return $this->hasMany(Municipality::class, 'province_code', 'code');
    }
}
