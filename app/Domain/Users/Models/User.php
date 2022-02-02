<?php

namespace App\Domain\Users\Models;

use App\Domain\AdminDivisions\Models\District;
use App\Domain\Applicants\Models\VerifierAssignment;
use App\Domain\CensusOffices\Models\CensusOffice;
use App\Domain\DailyReports\Models\DailyReport;
use App\Domain\Enumerators\Models\Enumerator;
use App\Domain\Enumerators\Repositories\EnumeratorRepository;
use App\Domain\Users\Models\Contracts\UserAccessor;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Helper;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Hashing\HashManager;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class User
 * @package App\Domain\Users\Models
 *
 * @property int               $id
 * @property string            $role
 * @property string            $name
 * @property string            $email
 * @property Carbon|null       $email_verified_at
 * @property string            $username
 * @property int               $district_code
 * @property int               $census_office_id
 * @property object            $metadata
 * @property Carbon            $created_at
 * @property Carbon            $updated_at
 * @property Carbon|null       $deleted_at
 * @property string            $status
 * @property string            $password
 * @property string            $mobile_number
 *
 * @property District|null     $district
 * @property CensusOffice|null $censusOffice
 * @property Collection        $unreadNotifications
 * @property Collection        $verificationAssignments
 * @property Collection        $dailyReports
 * @property Collection        $enumerators
 *
 * @property string            $display_name
 * @property string|null       $profile_photo_path
 * @property string            $profile_photo_url
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class User extends Authenticatable implements Auditable
{
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;
    use AuditableTrait;
    use UserAccessor;
    use HasApiTokens;

    /**
     * @var string
     */
    protected $table = DBTables::AUTH_USERS;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role',
        'name',
        'email',
        'username',
        'password',
        'district_code',
        'census_office_id',
        'status',
        'mobile_number',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'email_verified_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    protected $casts = [
        'metadata' => 'json',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return UserFactory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
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

    /**
     * @return HasMany
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function verificationAssignments(): HasMany
    {
        return $this->hasMany(VerifierAssignment::class, 'verifier_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function dailyReports(): HasMany
    {
        return $this->hasMany(DailyReport::class, 'created_by', 'id');
    }

    public function enumerators(): HasMany
    {
        return $this->hasMany(Enumerator::class, 'supervisor_id', 'id');
    }

    /**
     * @param string $password
     *
     * @throws BindingResolutionException
     */
    public function setPasswordAttribute(string $password): void
    {
        /** @var HashManager $hashManager */
        $hashManager                  = app()->make(HashManager::class);
        $this->attributes['password'] = $hashManager->needsRehash($password) ? $hashManager->make($password) : $password;
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path ? Helper::fileUrl($this->profile_photo_path) : $this->defaultProfilePhotoUrl();
    }

    /**
     * Get the disk that profile photos should be stored on.
     *
     * @return string
     */
    protected function profilePhotoDisk()
    {
        return config('filesystems.default');
    }
}
