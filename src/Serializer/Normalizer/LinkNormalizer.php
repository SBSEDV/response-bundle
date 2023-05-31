<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Serializer\Normalizer;

use SBSEDV\Bundle\ResponseBundle\Model\Link;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LinkNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    /**
     * @param Link $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $href = $object->href;

        if (!\str_starts_with($href, 'http')) {
            $href = $this->urlGenerator->generate($href, $object->routeParams, UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return [...$object->other, 'href' => $href];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Link;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Link::class => __CLASS__ === static::class,
        ];
    }
}
