<?php

namespace App\Domain\Applicants\Models;

use App\Domain\AdminDivisions\Models\Municipality;
use App\Domain\Applications\Models\Application;
use App\Infrastructure\Constants\DBTables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ShortListedApplicant
 *
 * @package App\Domain\Applicants\Models
 *
 * @property int                 $id
 * @property int                 $applicant_id
 * @property int                 $municipality_code
 * @property int                 $score
 * @property int                 $rank
 * @property string              $role
 * @property bool                $is_shortlisted
 * @property string|null         $hiring_status
 * @property object|null         $metadata
 * @property Carbon              $created_at
 * @property Carbon              $updated_at
 * @property Carbon|null         $deleted_at
 *
 * @property Municipality|null   $municipality
 * @property ApplicationListView $applicationView
 */
class ShortListedApplicant extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = DBTables::SHORTLISTED;

    /**
     * @var string[]
     */
    protected $fillable = [
        'applicant_id',
        'municipality_code',
        'role',
        'is_shortlisted',
        'hiring_status',
        'metadata',
        'rank',
        'score',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'is_shortlisted' => 'bool',
        'metadata'       => 'object',
    ];

    /**
     * @return HasOne
     */
    public function application(): HasOne
    {
        return $this->hasOne(Application::class, 'applicant_id', 'applicant_id');
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
    public function applicationView(): BelongsTo
    {
        return $this->belongsTo(ApplicationListView::class, 'applicant_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class, 'municipality_code', 'code');
    }
}
