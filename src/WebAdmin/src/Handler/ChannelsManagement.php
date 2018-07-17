<?php

declare(strict_types=1);

namespace FTC\WebAdmin\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template;
use Zend\Expressive\Template\TemplateRendererInterface;
use FTC\Discord\Model\Aggregate\Guild;
use FTC\Discord\Model\ValueObject\Snowflake\RoleId;
use FTC\Discord\Model\Aggregate\GuildChannelRepository;
use Psr\Http\Server\MiddlewareInterface;

class ChannelsManagement implements MiddlewareInterface
{
    
    /**
     * @var guildChannelRepository $channelsRepositoryy
     */
    private $channelsRepository;
    
    /**
     * @var TemplateRendererInterface
     */
    private $template;
    

    public function __construct(
        Template\TemplateRendererInterface $template = null,
        GuildChannelRepository $guildChannelRepository
    ) {
        $this->template = $template;
        $this->channelsRepository = $guildChannelRepository;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $guild = $request->getAttribute(Guild::class);
        $roles = $this->channelsRepository->getAll($guild->getId())->displayOrder();
        
        if ($selectedRoleId = (int) $request->getAttribute('roleId')) {
            $data['selectedRole'] = $roles->getById(RoleId::create($selectedRoleId));
        }
        
        $data['roles'] = $roles;

        
        return new HtmlResponse($this->template->render('admin::channels-page', $data));
    }
}
