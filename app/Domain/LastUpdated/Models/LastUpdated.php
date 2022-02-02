<?php

namespace App\Domain\LastUpdated\Models;

use App\Infrastructure\Constants\DBTables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LastUpdated
 * @package App\Domain\LastUpdated\Models
 *
 * @property int    $id
 * @property string $updated_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class LastUpdated extends Model
{
    /**
     * @var string
     */
    protected $table = DBTables::LAST_UPDATED_TABLE;

    /**
     * @var string[]
     */
    protected $fillable = [
        'updated_name',
    ];
}
