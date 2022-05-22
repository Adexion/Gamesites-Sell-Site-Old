<?php

namespace App\EventSubscriber;

use App\Enum\UrlEnum;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CatchExpiredOfSubscriber implements EventSubscriberInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['forceBlockingApplication'],
        ];
    }

    public function forceBlockingApplication(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if (str_contains($event->getRequest()->getUri(), 'admin')) {
            return;
        }

        $response = json_decode(file_get_contents(UrlEnum::GAMESITES_URL . 'v1/application/information/' . $_ENV['COUPON']), true);
        if (!empty($response) && $response['turnOffDate'] < date('Y-m-d')) {
            $content = $this->container->get('twig')->render('error402.html.twig');

            $response = new Response();
            $response->setContent($content);

            $event->setResponse($response);
        }
    }
}