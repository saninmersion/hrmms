<?php

namespace App\Domain\Monitorings\Models;

use App\Domain\AdminDivisions\Models\District;
use App\Domain\AdminDivisions\Models\Municipality;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\DBTables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class Monitoring
 * @package App\Domain\Monitorings\Models
 *
 * @property int               $id
 * @property string            $monitoring_form
 * @property Carbon            $monitoring_date
 * @property int               $district_code
 * @property int               $municipality_code
 * @property string            $ward
 * @property string            $census_area
 * @property object            $monitoring_data
 * @property int               $monitored_by_id
 * @property int               $entered_by_id
 * @property Carbon            $created_at
 * @property Carbon            $updated_at
 * @property Carbon|null       $deleted_at
 *
 * @property User|null         $monitoredBy
 * @property District|null     $monitoredDistrict
 * @property Municipality|null $monitoredMunicipality
 */
class Monitoring extends Model implements Auditable
{
    use AuditableTrait;
    use SoftDeletes;
    use HasFactory;

    protected $table = DBTables::MONITORINGS;

    protected $fillable = [
        'monitoring_form',
        'monitoring_date',
        'district_code',
        'municipality_code',
        'ward',
        'census_area',
        'monitoring_data',
        'monitored_by_id',
        'entered_by_id',
        'metadata',
    ];

    protected $dates = ['monitoring_date'];

    protected $casts = [
        'monitoring_data' => 'object',
    ];

    /**
     * @return BelongsTo
     */
    public function monitoredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'monitored_by_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function monitoredDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

    public function monitoredMunicipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class, 'municipality_code', 'code');
    }
}
