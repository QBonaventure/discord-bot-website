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
use FTC\Discord\Model\ValueObject\Snowflake\ChannelId;
use FTC\Discord\Model\Aggregate\GuildMessageRepository;
use FTC\Discord\Model\Aggregate\GuildMemberRepository;

class ChannelsManagement implements MiddlewareInterface
{
    
    /**
     * @var guildChannelRepository $channelsRepositoryy
     */
    private $channelsRepository;
    
    /**
     * @var GuildMessageRepository
     */
    private $guildMessageRepository;
    
    /**
     * @var GuildMemberRepository
     */
    private $guildMembersRepository;
    
    /**
     * @var TemplateRendererInterface
     */
    private $template;
    

    public function __construct(
        Template\TemplateRendererInterface $template = null,
        GuildChannelRepository $guildChannelRepository,
        GuildMessageRepository $guildMessageRepository,
        GuildMemberRepository $guildMembersRepository
        ) {
        $this->template = $template;
        $this->channelsRepository = $guildChannelRepository;
        $this->guildMessageRepository = $guildMessageRepository;
        $this->guildMembersRepository = $guildMembersRepository;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $guild = $request->getAttribute(Guild::class);
        $channels = $this->channelsRepository->getAll($guild->getId())->displayOrder();
        
        if ($selectedRoleId = (int) $request->getAttribute('channelId')) {
            $channelId = ChannelId::create($selectedRoleId);
            $data['selectedChannel'] = $channels->getById($channelId);
            $data['selectedChannelMessages'] = $this->guildMessageRepository->getAllForChannel($channelId);
            $data['members'] = $this->guildMembersRepository->getAll($guild->getId());
        }
        
        $data['channels'] = $channels;

        
        return new HtmlResponse($this->template->render('admin::channels-page', $data));
    }
}
