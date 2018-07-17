<?php

declare(strict_types=1);

namespace FTC\WebAdmin;


use App\Container\Middleware\ActionAbstractFactory;

class ConfigProvider
{

    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            'predispatch-pipe' => [
                Handler\RbacManagement::class => [
                    Middleware\RbacRemoveRole::class,
                    Middleware\RbacAddRole::class,
                ],
            ],
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
                Handler\HomePage::class => Container\Handler\HomePageFactory::class,
                Handler\RolesManagement::class => ActionAbstractFactory::class,
                Handler\ChannelsManagement::class => ActionAbstractFactory::class,
                Handler\MembersManagement::class => ActionAbstractFactory::class,
                Handler\RbacManagement::class => ActionAbstractFactory::class,
                Middleware\RbacRemoveRole::class => Container\Middleware\RbacRemoveRoleFactory::class,
                Middleware\RbacAddRole::class => Container\Middleware\RbacAddRoleFactory::class,
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
