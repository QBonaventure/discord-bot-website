<?php
return [
    'dependencies' => [
        'factories' => [
            App\EventListener\GuildWebsitePermissionRemoved::class => App\EventListener\GuildWebsitePermissionRemoved::class,
            App\EventListener\NotifierListener::class => App\Container\EventListener\NotifierListenerFactory::class,
        ]
    ]
];
