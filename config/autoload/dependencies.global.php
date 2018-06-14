<?php declare(strict_types=1);

return [
    'dependencies' => [
        'aliases' => [
        ],
        'invokables' => [
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories'  => [
            App\Handler\LoginHandler::class => App\Handler\LoginHandlerFactory::class,
            'database' => FTC\Database\ClientFactory::class,
            'discord_oauth' => FTC\Discord\OAuthFactory::class,
        ],
    ],
];
