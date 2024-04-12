<?php

namespace Drupal\symfony_debug\EventSubscriber;

use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class KernelEventSubscriber.
 *
 * @package Drupal\symfony_debug\EventSubscriber
 */
class KernelEventSubscriber implements EventSubscriberInterface {

  /**
   * KernelEvents::EXCEPTION event handler.
   *
   * @param \Symfony\Component\HttpKernel\Event\ExceptionEvent $event
   *   Event.
   *
   * @throws \Exception
   */
  public function onException(ExceptionEvent $event) {
    $renderer = new HtmlErrorRenderer(TRUE, NULL, NULL, DRUPAL_ROOT);
    $exception = $renderer->render($event->getThrowable());

    // Return a response before the default exception handler can do it.
    $event->setResponse(
      new Response($exception->getAsString(), $exception->getStatusCode(), $exception->getHeaders())
    );
  }

  /**
   * KernelEvents::REQUEST event handler.
   */
  public function onRequest() {
    // This needs to be called as early as possible in order to catch errors.
    // phpcs:ignore
    $handler = Debug::enable();

    // Force Symfony error handler instead of Drupal's one.
    $handler->setExceptionHandler([$handler, 'renderException']);
  }

  /**
   * Get subscribed events.
   *
   * @return array
   *   Subscribed events
   */
  public static function getSubscribedEvents(): array {
    return [
      // Just after ExceptionListener.
      KernelEvents::EXCEPTION => ['onException', -129],
      KernelEvents::REQUEST => 'onRequest',
    ];
  }

}
