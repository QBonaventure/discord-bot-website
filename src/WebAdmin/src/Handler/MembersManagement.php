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
use FTC\Discord\Model\Aggregate\GuildMemberRepository;
use FTC\Discord\Model\Aggregate\GuildRoleRepository;

class MembersManagement implements RequestHandlerInterface
{
    
    /**
     * @var GuildMemberRepository $membersRepositoryy
     */
    private $membersRepository;
    
    /**
     * @var GuildRoleRepository
     */
    private $rolesRepository;
    
    /**
     * @var TemplateRendererInterface
     */
    private $template;
    

    public function __construct(
        Template\TemplateRendererInterface $template = null,
        GuildMemberRepository $guildChannelRepository,
        GuildRoleRepository $rolesRepository
    ) {
        $this->template = $template;
        $this->membersRepository = $guildChannelRepository;
        $this->rolesRepository = $rolesRepository;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $guild = $request->getAttribute(Guild::class);
        $members = $this->membersRepository->getAll($guild->getId())->orderAlphabetically();
        $roles = $this->rolesRepository->getAll($guild->getId());
        
        if ($selectedRoleId = (int) $request->getAttribute('memberId')) {
            $data['selectedMember'] = $members->getById(RoleId::create($selectedRoleId));
        }
        
        $data['members'] = $members;
        $data['roles'] = $roles;
        
        return new HtmlResponse($this->template->render('admin::members-page', $data));
    }
}
