<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class DefaultRequestFormatEventListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly bool $isDebug,
        private readonly string $defaultFormat
    ) {
    }

    /**
     * Set the default request format.
     *
     * @param RequestEvent $event The "kernel.request" event.
     */
    public function __invoke(RequestEvent $event): void
    {
        if (!$this->isDebug) {
            return;
        }

        $request = $event->getRequest();

        if (null === $request->getPreferredFormat(null)) {
            $request->setRequestFormat($this->defaultFormat);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['__invoke', 4096],
        ];
    }
}
