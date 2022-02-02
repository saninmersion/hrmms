<?php
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

namespace App\Domain\Applicants\Models;

use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\DBTables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DownloadLog
 * @package App\Domain\Applicants\Models
 *
 * @property int             $id
 * @property int             $export_id
 * @property int             $user_id
 * @property Carbon          $created_at
 * @property Carbon          $updated_at
 *
 * @property User            $downloaded_by
 * @property ApplicantExport $exported_file
 *
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class DownloadLog extends Model
{
    /**
     * @var string
     */
    protected $table = DBTables::APPLICATION_DOWNLOAD_LOGS;

    /**
     * @var string[]
     */
    protected $fillable = [
        'export_id',
        'user_id',
    ];

    /**
     * @return BelongsTo
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function downloaded_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     * @noinspection PhpMethodNamingConventionInspection
     */
    public function exported_file(): BelongsTo
    {
        return $this->belongsTo(ApplicantExport::class, 'export_id', 'id');
    }
}
