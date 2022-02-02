<?php


namespace App\Domain\HouseDailyReports\Models;


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
 * Class HouseDailyReport
 *
 * @package App\Domain\HouseDailyReports\Models
 *
 * @property int          $id
 * @property int          $created_by
 * @property int          $total_surveyed
 * @property int          $number_of_houses_in_census
 * @property int          $number_of_families_in_census
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
class HouseDailyReport extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;

    protected $table = DBTables::HOUSE_DAILY_REPORTS;

    protected $fillable = [
        'created_by',
        'total_surveyed',
        'number_of_houses_in_census',
        'number_of_families_in_census',
        'report_date',
        'district_code',
        'census_office_id',
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
}
