<?php
return [
    'dependencies' => [
        'factories' => [
            'CommandBus' => App\Container\CommandBusFactory::class,
            App\CommandHandler\RemoveGuildWebsitePermissionHandler::class => App\Container\CommandHandler\RemoveGuildWebsitePermissionHandlerFactory::class,
            App\CommandHandler\AddGuildWebsitePermissionHandler::class => App\Container\CommandHandler\AddGuildWebsitePermissionHandlerFactory::class,
//             App\CommandHandler\Add
        ],
    ],
];
