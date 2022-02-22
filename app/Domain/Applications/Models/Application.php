<?php
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

namespace App\Domain\Applications\Models;

use App\Domain\AdminDivisions\Models\District;
use App\Domain\AdminDivisions\Models\Municipality;
use App\Domain\Applicants\Models\Applicant;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Constants\StatusTypes;
use Carbon\Carbon;
use Database\Factories\ApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

/**
 * Class Application
 *
 * @package App\Domain\Applications\Models
 *
 * @property int               $id
 * @property int               $applicant_id
 * @property bool              $for_supervisor
 * @property bool              $for_enumerator
 * @property object            $locations
 * @property string            $status
 * @property Carbon|null       $application_date
 * @property object            $official
 * @property Carbon            $created_at
 * @property Carbon            $updated_at
 * @property Carbon|null       $deleted_at
 * @property bool              $is_offline
 * @property int               $entered_by_id
 *
 * @property Applicant         $applicant
 * @property District|null     $first_priority_district
 * @property Municipality|null $first_priority_municipality
 * @property District|null     $second_priority_district
 * @property Municipality|null $second_priority_municipality
 *
 * @property string|null       $application_for
 * @property array             $locations_array
 * @property bool              $is_submitted
 * @property string            $submission_number
 * @property string            $application_date_formatted
 *
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class Application extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use HasJsonRelationships;

    /**
     * @var string
     */
    protected $table = DBTables::APPLICATIONS;

    /**
     * @var string[]
     */
    protected $fillable = [
        'applicant_id',
        'for_supervisor',
        'for_enumerator',
        'locations',
        'status',
        'application_date',
        'official',
        'is_offline',
        'entered_by_id',
    ];

    /**
     * @var string[]
     */
    protected $dates = ['application_date'];

    /**
     * @var string[]
     */
    protected $casts = [
        'for_supervisor' => 'boolean',
        'for_enumerator' => 'boolean',
        'locations'      => 'object',
        'official'       => 'object',
    ];

    /**
     * @var string[]
     */
    protected $appends = ['application_for'];

    /**
     * Create a new factory instance for the model.
     *
     * @return ApplicationFactory
     */
    protected static function newFactory()
    {
        return ApplicationFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'id');
    }

    /**
     * @return BelongsTo
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function first_priority_district(): BelongsTo
    {
        return $this->belongsTo(District::class, "locations->first->district", 'code');
    }

    /**
     * @return BelongsTo
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function first_priority_municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class, "locations->first->municipality", 'code');
    }

    /**
     * @return BelongsTo
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function second_priority_district(): BelongsTo
    {
        return $this->belongsTo(District::class, "locations->second->district", 'code');
    }

    /**
     * @return BelongsTo
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function second_priority_municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class, "locations->second->municipality", 'code');
    }

    /**
     * @return string|null
     */
    public function getApplicationForAttribute(): ?string
    {
        if ( $this->for_enumerator && $this->for_supervisor ) {
            return ApplicationType::ENUMERATOR_SUPERVISOR;
        }

        if ( $this->for_enumerator && !$this->for_supervisor ) {
            return ApplicationType::ENUMERATOR;
        }

        if ( !$this->for_enumerator && $this->for_supervisor ) {
            return ApplicationType::SUPERVISOR;
        }

        return null;
    }

    /**
     * @return array
     */
    public function getLocationsArrayAttribute(): array
    {
        $locations      = [];
        $locations[]    = data_get($this->locations, 'first');
        $locations[]    = data_get($this->locations, 'second');
        $locationsArray = [];

        foreach ($locations as $index => $location) {
            if ( !$location ) {
                continue;
            }

            $locationsArray[] = [
                'priority'     => $index + 1,
                'district'     => (int) (data_get($location, "district", null)) ?: null,
                'municipality' => (int) (data_get($location, "municipality", null)) ?: null,
                'ward'         => (int) (data_get($location, "ward", null)) ?: null,
            ];
        }

        return $locationsArray;
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsSubmittedAttribute(): bool
    {
        $status = $this->status ?: StatusTypes::APPLICATION_DRAFT;
        if ( $status !== StatusTypes::APPLICATION_DRAFT && $this->application_date ) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getSubmissionNumberAttribute(): string
    {
        return sprintf("NSCA-%07d", $this->id);
    }

    /**
     * @return string
     */
    public function getApplicationDateFormattedAttribute(): string
    {
        return $this->application_date ? $this->application_date->format('Y/m/d') : '';
    }
}
