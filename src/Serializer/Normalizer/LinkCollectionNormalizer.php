<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Serializer\Normalizer;

use SBSEDV\Bundle\ResponseBundle\Model\LinkCollection;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LinkCollectionNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * {@inheritdoc}
     *
     * @param LinkCollection $object
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        $data = [];

        foreach ($object->getIterator() as $name => $link) {
            $data[$name] = $this->normalizer->normalize($link, $format, $context);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof LinkCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
