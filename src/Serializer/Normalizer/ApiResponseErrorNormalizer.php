<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Serializer\Normalizer;

use SBSEDV\Bundle\ResponseBundle\Response\ApiResponseErrorDto;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiResponseErrorNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly ?NameConverterInterface $nameConverter
    ) {
    }

    /**
     * @param ApiResponseErrorDto $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $msg = $object->message;

        if ($msg instanceof TranslatableInterface) {
            $msg = $msg->trans($this->translator);
        }

        $error = [
            'message' => $msg,
            'type' => $this->convertName($object->type),
        ];

        if (null !== $object->cause) {
            $error['cause'] = $this->convertName($object->cause);
        }

        foreach ($object->other as $key => $value) {
            $error[$this->convertName($key)] = $value;
        }

        return $error;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof ApiResponseErrorDto;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    private function convertName(string $name): string
    {
        if (null === $this->nameConverter) {
            return $name;
        }

        return $this->nameConverter->normalize($name);
    }
}
