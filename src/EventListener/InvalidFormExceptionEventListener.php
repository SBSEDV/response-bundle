<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\EventListener;

use SBSEDV\Bundle\ResponseBundle\Exception\InvalidFormException;
use SBSEDV\Bundle\ResponseBundle\Response\ApiResponseDto;
use SBSEDV\Bundle\ResponseBundle\Response\ApiResponseErrorDto;
use SBSEDV\Bundle\ResponseBundle\Response\ApiResponseFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatableInterface;

class InvalidFormExceptionEventListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly NormalizerInterface $normalizer,
        private readonly ApiResponseFactory $apiResponseFactory,
    ) {
    }

    /**
     * Serialize an invalid form instance into an http response.
     *
     * @param ExceptionEvent $event The "kernel.exception" event.
     */
    public function __invoke(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        if (!($e instanceof InvalidFormException)) {
            return;
        }

        $form = $e->getForm();

        $formErrors = $this->normalizer->normalize($form);

        if (!\is_array($formErrors)) {
            return;
        }

        $errors = [];

        /** @var array{'message': string|TranslatableInterface, 'cause'?: string, 'type'?: string, 'cause'?: string} $formError */
        foreach ($formErrors as $formError) {
            $dto = new ApiResponseErrorDto($formError['message'], 'invalid_request_error', $formError['cause'] ?? null);

            /** @var string $key */
            foreach ($formError as $key => $value) {
                if (\in_array($key, ['message', 'type', 'cause'], true)) {
                    continue;
                }

                $dto->other[$key] = $value;
            }

            $errors[] = $dto;
        }

        $apiResponse = new ApiResponseDto(
            new TranslatableMessage('request_has_errors', ['count' => \count($errors)], 'sbsedv_response'),
            null,
            $errors,
            Response::HTTP_BAD_REQUEST
        );

        $event->setResponse($this->apiResponseFactory->createApiResponse($apiResponse));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => ['__invoke', 2048],
        ];
    }
}
