<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $html = <<<EOL
<html>
<body>
    <div style="text-align: center; font-size: 3em; color: blue;">
        YAY!
    </div>
</body>
</html>
EOL;

        $response = new Response($html);

        //$event->setResponse($response);
        //$event->stopPropagation();
    }

    public function onKernelControllerArguments(ControllerArgumentsEvent $event)
    {
        //
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 9999],
            KernelEvents::CONTROLLER_ARGUMENTS => ['onKernelControllerArguments', 10],
        ];
    }
}
