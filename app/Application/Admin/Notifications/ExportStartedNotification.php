<?php

namespace App\Application\Admin\Notifications;

use App\Domain\Users\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notification;

/**
 * Class ExportStartedNotification
 * @package App\Application\Admin\Notifications
 */
class ExportStartedNotification extends Notification
{
    /**
     * @var Authenticatable|User|null
     */
    protected $user;

    /**
     * @var array
     */
    protected array $exportDetail = [];

    /**
     * ExportStartedNotification constructor.
     *
     * @param Authenticatable|User|null $user
     * @param array                     $exportDetail
     */
    public function __construct($user, array $exportDetail)
    {
        $this->user         = $user;
        $this->exportDetail = $exportDetail;
    }

    /**
     * @return string[]
     */
    public function via()
    {
        return ['database'];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name'    => data_get($this->user, 'name'),
            'email'   => data_get($this->user, 'email'),
            'message' => sprintf("Your export of %s has started. You will be notified when the export is complete.", $this->exportDetail['type']),
        ];
    }
}
