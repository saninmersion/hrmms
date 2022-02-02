<?php

namespace App\Application\Admin\Notifications;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notification;

/**
 * Class ExportCompletedNotification
 * @package App\Application\Admin\Notifications
 */
class ExportCompletedNotification extends Notification
{
    /**
     * @var Authenticatable|null
     */
    protected $user;

    /**
     * @var string
     */
    protected string $fileUrl;

    /**
     * @var array
     */
    protected array $exportDetails;

    /**
     * ExportStartedNotification constructor.
     *
     * @param Authenticatable|null $user
     * @param string               $fileUrl
     * @param array                $exportDetails
     */
    public function __construct($user, string $fileUrl, $exportDetails)
    {
        $this->user          = $user;
        $this->fileUrl       = $fileUrl;
        $this->exportDetails = $exportDetails;
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
            'message' => $this->getMessage(),
        ];
    }

    /**
     * @return string
     */
    protected function getMessage()
    {
        return sprintf("Your export of %s has completed. Please <a href='%s' class='text-info-500' download> click here </a> to download it.", $this->exportDetails['type'], $this->fileUrl);
    }
}
