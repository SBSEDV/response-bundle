<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Serializer\Normalizer;

use SBSEDV\Bundle\ResponseBundle\Exception\HttpException;
use SBSEDV\Bundle\ResponseBundle\Response\ApiResponseDto;
use SBSEDV\Bundle\ResponseBundle\Response\ApiResponseErrorDto;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProblemNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @param FlattenException $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        /** @var bool $debug */
        $debug = $context['debug'];

        if ($debug) {
            // so that in debug mode the HTML exception page is rendered
            throw new NotEncodableValueException();
        }

        $errorMsg = $errorType = null;

        if (\is_a($object->getClass(), HttpExceptionInterface::class, true)) {
            $errorMsg = \trim($object->getMessage());
            if ($errorMsg === '') {
                $errorMsg = null;
            }

            $statusText = Response::$statusTexts[$object->getStatusCode()] ?? null;

            if (null !== $statusText) {
                $errorType = \strtolower(\str_replace([' ', '-', '\''], ['_', '_', ''], $statusText));
            }
        }

        // some exceptions may contain sensitive data in their message
        // example: PDO connection error leaks database password
        // Thats why we generate a generic one. Don't worry:
        // The original exception and its message has already been logged when this point is reached.
        $msg = $this->translator->trans('unexpected_error', domain: 'sbsedv_response');

        $error = new ApiResponseErrorDto($errorMsg ?? $msg, $errorType ?? 'server_error');

        $response = new ApiResponseDto($msg, null, [$error], $object->getStatusCode());

        return $this->normalizer->normalize($response, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException && (!($context['exception'] ?? null) instanceof HttpException);
    }
}
