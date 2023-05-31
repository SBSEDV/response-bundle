<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Serializer\Normalizer;

use SBSEDV\Bundle\ResponseBundle\Exception\BundledHttpException;
use SBSEDV\Bundle\ResponseBundle\Exception\HttpException;
use SBSEDV\Bundle\ResponseBundle\Response\ApiResponseDto;
use SBSEDV\Bundle\ResponseBundle\Response\ApiResponseErrorDto;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Translation\TranslatableMessage;

class HttpExceptionNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param FlattenException $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        /** @var HttpException $e */
        $e = $context['exception'];

        $errors = [];

        if ($e instanceof BundledHttpException) {
            foreach ($e->getExceptions() as $child) {
                $errors[] = $this->createErrorFromException($child);
            }

            // use the wrapped exception message
            // if it is empty, use the generic "request_has_errors" message.
            $message = $e->getTranslatable() ?? $e->getMessage();
            if ($message === '') {
                $message = new TranslatableMessage('request_has_errors', ['count' => \count($errors)], 'sbsedv_response');
            }
        } else {
            $errors[] = $this->createErrorFromException($e);
            $message = new TranslatableMessage('request_has_errors', ['count' => \count($errors)], 'sbsedv_response');
        }

        $response = new ApiResponseDto($message, null, $errors, $object->getStatusCode());

        // @phpstan-ignore-next-line
        return $this->normalizer->normalize($response, $format, $context);
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException && isset($context['exception']) && $context['exception'] instanceof HttpException;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            FlattenException::class => __CLASS__ === static::class,
        ];
    }

    /**
     * Create an ApiResponseErrorDto from an HttpException.
     *
     * @param HttpException $e The exception from which to create the error.
     */
    private function createErrorFromException(HttpException $e): ApiResponseErrorDto
    {
        $error = new ApiResponseErrorDto($e->getTranslatable() ?? $e->getMessage(), $e->getType());

        if (null !== $e->getCause()) {
            $error->cause = $e->getCause();
        }

        foreach ($e->getOther() as $key => $value) {
            $error->other[$key] = $value;
        }

        if ($e instanceof \JsonSerializable) {
            $serialized = $e->jsonSerialize();

            if (\is_array($serialized)) {
                foreach ($serialized as $key => $value) {
                    $error->other[$key] = $value;
                }
            }
        }

        return $error;
    }
}
