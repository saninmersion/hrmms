<?php


namespace App\Domain\Enumerators\Models;


use App\Domain\DailyReports\Models\DailyReport;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class Enumerator
 * @package App\Domain\Enumerators\Models
 *
 * @property int    $id
 * @property string $name
 * @property int    $target
 * @property int    $supervisor_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property User   $supervisor
 *
 * @property int    $total_progress
 */
class Enumerator extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = DBTables::ENUMERATORS;

    protected $fillable = [
        'name',
        'target',
        'supervisor_id',
        'metadata',
    ];

    protected $appends = [
        'total_progress',
    ];

    /**
     * @return BelongsTo
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dailyReports(): HasMany
    {
        return $this->hasMany(DailyReport::class, 'enumerator_id', 'id');
    }

    public function getTotalProgressAttribute(): int
    {
        return $this->dailyReports()->sum('total_surveyed');
    }
}
