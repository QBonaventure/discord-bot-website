<?php declare(strict_types=1);

namespace App\EventListener;
use LeonardoTeixeira\Pushover\Client;
use LeonardoTeixeira\Pushover\Message;
use Zend\EventManager\Event;
use LeonardoTeixeira\Pushover\Sound;
use LeonardoTeixeira\Pushover\Priority;

class NotifierListener
{
    
    private $pushoverClient;
    
    public function __construct(Client $pushoverclient)
    {
        $this->pushoverClient = $pushoverclient;
    }
    
    public function onGuildWebsitePermissionRemoved(Event $event)
    {
        $cmd = $event->getParam('command');
        $message = new Message(sprintf('Permission %s for route %s removed !', $cmd->getPermission()->getRoleId(), $cmd->getPermission()->getRouteName()));
        $message->setTitle('Website permission removed');
        $message->setPriority(Priority::HIGH);
        $message->setSound(Sound::GAMELAN);
        $message->setDate(new \DateTime());
    
        try {
            $this->pushoverClient->push($message);
        } catch (PushoverException $e) {
            var_dump($e);
        }
    }
}