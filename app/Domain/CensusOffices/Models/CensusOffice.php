<?php

namespace App\Domain\CensusOffices\Models;

use App\Domain\AdminDivisions\Models\District;
use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CensusOffice
 *
 * @package App\Domain\CensusOffices\Models
 *
 * @property int    $id
 * @property int    $district_code
 * @property string $office_name
 *
 */
class CensusOffice extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = DBTables::CENSUS_OFFICES;

    /**
     * @var string[]
     */
    protected $fillable = [
        'district_code',
        'office_name',
    ];

    /**
     * @return BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }
}
