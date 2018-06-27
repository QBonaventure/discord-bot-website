<?php
declare(strict_types=1);

return [
    'dependencies' => [
        'aliases' => [
        ],
        'invokables' => [
            App\Session\Handler\LogoutHandler::class => App\Session\Handler\LogoutHandler::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories'  => [
            FTC\Discord\Model\GuildMemberRepository::class => FTC\Discord\Db\Postgresql\Factory\GuildMemberRepository::class,
            App\Session\Handler\LoginHandler::class => App\Session\Handler\LoginHandlerFactory::class,
            'database' => FTC\Database\ClientFactory::class,
            'discord_oauth' => FTC\Discord\OAuthFactory::class,
        ],
    ],
];
