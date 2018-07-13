<?php

declare(strict_types=1);

namespace FTC\WebAdmin;


class ConfigProvider
{

    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    public function getDependencies() : array
    {
        return [
            'delegators' => [
                \Zend\Expressive\Application::class => [
                    Container\RoutesDelegator::class,
                ],
            ],
            'invokables' => [
            ],
            'factories'  => [
                Handler\HomePage::class => Handler\HomePageFactory::class,
                Handler\RolesManagement::class => Handler\RolesManagementFactory::class,
                Handler\ChannelsManagement::class => Handler\ChannelsManagementFactory::class,
                Handler\MembersManagement::class => Handler\MembersManagementFactory::class,
            ],
        ];
    }

    public function getTemplates() : array
    {
        return [
            'paths' => [
                'admin'    => [__DIR__ . '/../templates/admin'],
                'partials'    => [__DIR__ . '/../templates/partials'],
            ],
        ];
    }
}
