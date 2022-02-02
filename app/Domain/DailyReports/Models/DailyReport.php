<?php


namespace App\Domain\DailyReports\Models;


use App\Domain\AdminDivisions\Models\District;
use App\Domain\CensusOffices\Models\CensusOffice;
use App\Domain\Enumerators\Models\Enumerator;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\DBTables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class DailyReport
 *
 * @package App\Domain\DailyReports\Models
 *
 * @property int          $id
 * @property int          $created_by
 * @property int          $total_surveyed
 * @property Carbon       $report_date
 * @property Carbon       $created_at
 * @property object       $metadata
 * @property int          $district_code
 * @property int          $census_office_id
 * @property int          $enumerator_id
 *
 * @property District     $district
 * @property CensusOffice $censusOffice
 * @property User         $supervisor
 * @property Enumerator   $enumerator
 */
class DailyReport extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;

    protected $table = DBTables::DAILY_REPORTS;

    protected $fillable = [
        'created_by',
        'total_surveyed',
        'report_date',
        'district_code',
        'census_office_id',
        'enumerator_id',
        'metadata',
    ];

    protected $dates = ['report_date'];

    /**
     * @return BelongsTo
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    /**
     * @return BelongsTo
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

    /**
     * @return BelongsTo
     */
    public function censusOffice(): BelongsTo
    {
        return $this->belongsTo(CensusOffice::class, 'census_office_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enumerator(): BelongsTo
    {
        return $this->belongsTo(Enumerator::class, 'enumerator_id', 'id');
    }
}
