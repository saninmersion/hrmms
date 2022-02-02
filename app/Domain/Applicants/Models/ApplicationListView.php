<?php

namespace App\Domain\Applicants\Models;

use App\Domain\Applicants\Accessors\ApplicationListViewFormattedAccessor;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Constants\StatusTypes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * Class ApplicationListView
 * @package App\Domain\Applicants\Models
 *
 * @property string      $id
 * @property string|null $submission_number
 * @property Carbon      $created_at
 * @property Carbon|null $application_date
 * @property string|null $application_for
 * @property string|null $status
 * @property int|null    $first_priority_district_code
 * @property string|null $first_priority_district
 * @property int|null    $first_priority_municipality_code
 * @property string|null $first_priority_municipality
 * @property int|null    $first_priority_ward
 * @property int|null    $second_priority_district_code
 * @property string|null $second_priority_district
 * @property int|null    $second_priority_municipality_code
 * @property string|null $second_priority_municipality
 * @property int|null    $second_priority_ward
 * @property object|null $name_in_english
 * @property object|null $name_in_nepali
 * @property string      $gender
 * @property string|null $ethnicity
 * @property string|null $mother_tongue
 * @property string|null $disability
 * @property Carbon|null $dob_ad
 * @property string      $dob_bs
 * @property string      $citizenship_number
 * @property int         $citizenship_issued_district_code
 * @property string      $citizenship_issued_district
 * @property string|null $citizenship_issued_date_bs
 * @property int|null    $permanent_address_district_code
 * @property string|null $permanent_address_district
 * @property int|null    $permanent_address_municipality_code
 * @property string|null $permanent_address_municipality
 * @property int|null    $permanent_address_ward
 * @property int|null    $temporary_address_district_code
 * @property string|null $temporary_address_district
 * @property int|null    $temporary_address_municipality_code
 * @property string|null $temporary_address_municipality
 * @property int|null    $temporary_address_ward
 * @property string      $mobile_number
 * @property object|null $mother_name
 * @property object|null $father_name
 * @property object|null $grand_father_name
 * @property object|null $spouse_name
 * @property bool|null   $degree_transcript_supervisor
 * @property string|null $major_subject_supervisor
 * @property string|null $major_subject_others_supervisor
 * @property string|null $grade_supervisor
 * @property string|null $percentage_supervisor
 * @property bool|null   $degree_transcript_enumerator
 * @property string|null $grade_enumerator
 * @property string|null $percentage_enumerator
 * @property bool|null   $degree_transcript_extra
 * @property string|null $major_subject_extra
 * @property string|null $major_subject_others_extra
 * @property bool|null   $has_training
 * @property bool|null   $training_documents
 * @property string|null $training_institute
 * @property string|null $training_period_in_days
 * @property bool|null   $has_experience
 * @property bool|null   $experience_documents
 * @property string|null $experience_organization
 * @property string|null $experience_period_month
 * @property string|null $experience_period_day
 *
 * @property bool        $is_submitted
 * @property string      $submitted_date_formatted
 * @property string      $application_for_formatted
 * @property string      $status_formatted
 * @property string      $name_in_english_formatted
 * @property string      $name_in_nepali_formatted
 * @property string      $dob_ad_formatted
 * @property string      $gender_formatted
 * @property string      $ethnicity_formatted
 * @property string      $mother_tongue_formatted
 * @property string      $disability_formatted
 * @property string      $mother_name_formatted
 * @property string      $father_name_formatted
 * @property string      $grand_father_name_formatted
 * @property string      $spouse_name_formatted
 * @property string      $has_degree_for_supervisor_formatted
 * @property string      $major_subject_supervisor_formatted
 * @property string      $has_degree_for_enumerator_formatted
 * @property string      $has_higher_degree_formatted
 * @property string      $major_subject_higher_formatted
 * @property string      $has_training_formatted
 * @property string      $has_training_documents_formatted
 * @property string      $has_experience_formatted
 * @property string      $has_experience_documents_formatted
 *
 * @property int         $score
 *
 * @property Collection  $shortlist
 */
class ApplicationListView extends Model
{
    use ApplicationListViewFormattedAccessor;

    /**
     * @var string
     */
    protected $table = DBTables::VIEW_APPLICATIONS;

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var string[]
     */
    protected $casts = [
        'name_in_english'              => 'object',
        'name_in_nepali'               => 'object',
        'mother_name'                  => 'object',
        'father_name'                  => 'object',
        'grand_father_name'            => 'object',
        'degree_transcript_supervisor' => 'bool',
        'degree_transcript_enumerator' => 'bool',
        'degree_transcript_extra'      => 'bool',
        'has_training'                 => 'bool',
        'training_documents'           => 'bool',
        'has_experience'               => 'bool',
        'experience_documents'         => 'bool',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'application_date',
        'dob_ad',
    ];


    /**
     * @return HasOne
     */
    public function verification()
    {
        return $this->hasOne(ApplicantVerification::class, 'applicant_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function shortlist(): HasMany
    {
        return $this->hasMany(ShortListedApplicant::class, 'applicant_id', 'id');
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
}
