<?php


namespace App\Domain\Applicants\Models;


use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\DBTables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class VerifierAssignment
 * @package App\Domain\Applicants\Models
 * @property int       $verifier_id
 * @property int       $from
 * @property int       $to
 * @property Carbon    $created_at
 * @property Carbon    $updated_at
 *
 * @property User      $verifier
 * @property Applicant $applicant
 */
class VerifierAssignment extends Model
{
    /**
     * @var string
     */
    protected $table = DBTables::VERIFIER_ASSIGNMENT;

    /**
     * @var array
     */
    protected $fillable = [
        'verifier_id',
        'applicant_id',
    ];

    /**
     * @return BelongsTo
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifier_id', 'id');
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
     */
    public function verification(): BelongsTo
    {
        return $this->belongsTo(ApplicantVerification::class, 'applicant_id', 'applicant_id');
    }
}
