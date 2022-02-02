<?php
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

namespace App\Domain\Applicants\Models;

use App\Domain\AdminDivisions\Models\District;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\DBTables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApplicationVerification
 * @package App\Domain\Applicants\Models
 *
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * @property int         $id
 * @property int         $applicant_id
 * @property int         $verifier_id
 * @property string      $remarks
 * @property string      $rejection_reason
 * @property string      $verification_status
 * @property object      $modified
 * @property object      $checklist
 * @property object      $metadata
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Applicant   $applicant
 * @property User        $verifier
 */
class ApplicantVerification extends Model
{
    use SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [
        'modified',
        'checklist',
        'metadata',
        'verification_status',
        'remarks',
        'rejection_reason',
        'applicant_id',
        'verifier_id',
    ];

    /**
     * @var string
     */
    protected $table = DBTables::APPLICANT_VERIFICATIONS;

    /**
     * @var string[]
     */
    protected $casts = [
        'modified'  => 'object',
        'checklist' => 'object',
        'metadata'  => 'object',
    ];

    /**
     * @return BelongsTo
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifier_id', 'id');
    }

    /**
     * @return string
     */
    public function getCitizenshipIssuedDistrictFormattedAttribute()
    {
        $district = District::where('code', $this->modified->citizenship_issued_district?? null)->first();

        return data_get($district, 'title_en', '');
    }
}
