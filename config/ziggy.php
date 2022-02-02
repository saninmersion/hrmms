<?php

return [
    'skip-route-function' => true,
    'whitelist'           => ['*'],
    'only'                => [
        'admin.*',
        'profile.*',
        'logout',
        'user-profile-information.*',
        'current-user-photo.*',
        'user-password.*',
        'front.application.*',
    ],
];
