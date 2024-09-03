<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

final class CheckBanListener
{
    #[AsEventListener(event: CheckPassportEvent::class)]
    public function onCheckPassportEvent(CheckPassportEvent $event): void
    {
       
        $passport = $event->getPassport();
        /**
         * @var User
         */
        $user = $passport->getUser();

        if($user->isBan()){
            throw new CustomUserMessageAuthenticationException("You have been banned 😂");
        }

    }
}
