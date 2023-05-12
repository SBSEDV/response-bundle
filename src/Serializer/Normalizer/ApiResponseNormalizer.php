<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Serializer\Normalizer;

use SBSEDV\Bundle\ResponseBundle\Response\ApiResponseDto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @param ApiResponseDto $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $msg = $object->message ?? Response::$statusTexts[$object->status];

        if ($msg instanceof TranslatableInterface) {
            $msg = $msg->trans($this->translator);
        }

        return [
            'message' => $msg,
            'data' => $this->normalizer->normalize($object->data, $format, $context),
            'errors' => $this->normalizer->normalize($object->errors, $format, $context),
            'status' => $object->status,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof ApiResponseDto;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
