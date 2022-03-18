<?php
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

namespace App\Domain\Applicants\Models;

use App\Domain\AdminDivisions\Models\District;
use App\Domain\AdminDivisions\Models\Municipality;
use App\Domain\Applicants\Accessors\ApplicantFormattedAccessor;
use App\Domain\Applications\Models\Application;
use App\Domain\Medias\Models\Media;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\BikramSambat\BikramSambat;
use App\Infrastructure\Support\Helper;
use Carbon\Carbon;
use Database\Factories\ApplicantFactory;
use Exception;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

/**
 * Class Applicant
 *
 * @package App\Domain\Applicants\Models
 *
 * @property int                $id
 * @property string             $citizenship_number
 * @property string             $citizenship_issued_date_bs
 * @property int                $citizenship_issued_district_code
 * @property string             $dob_bs
 * @property string             $mobile_number
 * @property object             $permanent_address
 * @property object             $temporary_address
 * @property object             $current_address
 * @property object             $details
 * @property object             $metadata
 * @property Carbon             $created_at
 * @property Carbon             $updated_at
 * @property Carbon|null        $deleted_at
 *
 * @property District|null      $citizenship_issued_district
 * @property Application|null   $application
 * @property District|null      $permanent_address_district
 * @property Municipality|null  $permanent_address_municipality
 * @property District|null      $current_address_district
 * @property Municipality|null  $current_address_municipality
 * @property VerifierAssignment $verification_assignment
 *
 * @property bool               $is_editable
 * @property string             $created_date_formatted
 * @property string             $last_date_formatted
 * @property Carbon             $last_date
 * @property int                $remaining_days
 * @property string             $name_in_english_formatted
 * @property string             $name_in_nepali_formatted
 * @property string             $ethnicity_formatted
 * @property string             $mother_tongue_formatted
 * @property string             $disability_formatted
 * @property string             $mother_name_formatted
 * @property string             $father_name_formatted
 * @property string             $grand_father_name_formatted
 * @property string             $spouse_name_formatted
 * @property string             $major_subject_supervisor_formatted
 * @property string             $major_subject_enumerator_formatted
 * @property string             $major_subject_extra_formatted
 *
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class Applicant extends Model implements Auditable, Authenticatable
{
    use AuthenticatableTrait;
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use HasJsonRelationships;
    use ApplicantFormattedAccessor;

    /**
     * @var string
     */
    protected $table = DBTables::AUTH_APPLICANTS;

    /**
     * @var string[]
     */
    protected $fillable = [
        'citizenship_number',
        'citizenship_issued_district_code',
        'citizenship_issued_date_bs',
        'dob_bs',
        'mobile_number',
        'metadata',
        'permanent_address',
        'temporary_address',
        'current_address',
        'details',
        'photo_file',

    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'metadata'          => 'object',
        'permanent_address' => 'object',
        'temporary_address' => 'object',
        'current_address'   => 'object',
        'details'           => 'object',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return ApplicantFactory
     */
    protected static function newFactory()
    {
        return ApplicantFactory::new();
    }

    /**
     * @return BelongsTo
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function citizenship_issued_district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'citizenship_issued_district_code', 'code');
    }

    /**
     * @return BelongsTo
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function permanent_address_district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'permanent_address->district', 'code');
    }

    /**
     * @return BelongsTo
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function permanent_address_municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class, 'permanent_address->municipality', 'code');
    }

    /**
     * @return BelongsTo
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function current_address_district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'current_address->district', 'code');
    }

    /**
     * @return BelongsTo
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function current_address_municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class, 'current_address->municipality', 'code');
    }

    /**
     * @return HasOne
     */
    public function application(): HasOne
    {
        return $this->hasOne(Application::class, 'applicant_id', 'id');
    }

    /**
     * @return MorphToMany
     */
    public function medias()
    {
        return $this->morphToMany(Media::class, 'mediaable', DBTables::SYS_MEDIAABLE);
    }

    /**
     * @return MorphToMany
     */
    public function citizenshipUploads()
    {
        return $this->morphToMany(Media::class, 'mediaable', DBTables::SYS_MEDIAABLE)->where(
            'field_name',
            'citizenship_uploads'
        );

    }

    /**
     * @return MorphToMany
     */
    public function plus2Uploads()
    {
        return $this->morphToMany(Media::class, 'mediaable', DBTables::SYS_MEDIAABLE)->where(
            'field_name',
            'plus_2_uploads'
        );

    }

    /**
     * @return MorphToMany
     */
    public function graduateUploads()
    {
        return $this->morphToMany(Media::class, 'mediaable', DBTables::SYS_MEDIAABLE)->where(
            'field_name',
            'graduate_uploads'
        );

    }

    /**
     * @return MorphToMany
     */
    public function higherEducationUploads()
    {
        return $this->morphToMany(Media::class, 'mediaable', DBTables::SYS_MEDIAABLE)->where(
            'field_name',
            'higher_education_uploads'
        );

    }

    /**
     * @return MorphToMany
     */
    public function applicantPhoto()
    {
        return $this->morphToMany(Media::class, 'mediaable', DBTables::SYS_MEDIAABLE)->orderBy('created_at')->where(
            'field_name',
            'applicant_photo'
        );

    }

    /**
     * @return HasOne
     */
    public function verification()
    {
        return $this->hasOne(ApplicantVerification::class, 'applicant_id', 'id');
    }

    /**
     * @return HasOne
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function verification_assignment(): HasOne
    {
        return $this->hasOne(VerifierAssignment::class, 'applicant_id', 'id');
    }

    /**
     * @return bool
     * @throws Exception
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsEditableAttribute(): bool
    {
        $isSubmitted = $this->application->is_submitted ?? false;
        if ( $isSubmitted ) {
            return false;
        }

        $bonusStartDate = BikramSambat::bsToAd(config('config.bonus-editable-start-date'));
        $bonusEndDate   = BikramSambat::bsToAd(config('config.bonus-editable-end-date'));

        if ( today()->between($bonusStartDate, $bonusEndDate) ) {
            return true;
        }

        $createdDate = $this->created_at->endOfDay();
        if ( $createdDate->addDays(config('config.editable-days'))->isPast() ) {
            return false;
        }

        $deadline = BikramSambat::bsToAd(config('config.deadline'));
        if ( !$deadline ) {
            return false;
        }

        $deadline = $deadline->endOfDay();
        if ( $deadline->isPast() ) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getCreatedDateFormattedAttribute(): string
    {
        return $this->created_at->format('Y/m/d');
    }

    /**
     * @return Carbon
     * @throws Exception
     */
    public function getLastDateAttribute(): Carbon
    {
        $deadline    = Helper::deadline();
        $createdDate = $this->created_at->endOfDay();
        $finalDate   = $createdDate->addDays((int) config('config.editable-days') - 1);

        if ( $deadline->lessThanOrEqualTo($finalDate) ) {
            return $deadline;
        }

        return $finalDate;
    }

    /**
     * @return string
     */
    public function getLastDateFormattedAttribute(): string
    {
        return $this->last_date->format('Y/m/d');
    }

    /**
     * @return int
     */
    public function getRemainingDaysAttribute(): int
    {
        if ( $this->last_date->endOfDay()->greaterThan(now()->endOfDay()) ) {
            return (int) ceil(now()->floatDiffInDays($this->last_date->endOfDay()));
        }

        return 0;
    }
}
