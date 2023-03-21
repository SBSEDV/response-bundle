<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SBSEDV\Bundle\ResponseBundle\Response\ApiResponseFactory;
use SBSEDV\Bundle\ResponseBundle\Serializer\Normalizer\ApiResponseErrorNormalizer;
use SBSEDV\Bundle\ResponseBundle\Serializer\Normalizer\ApiResponseNormalizer;
use SBSEDV\Bundle\ResponseBundle\Serializer\Normalizer\LinkCollectionNormalizer;
use SBSEDV\Bundle\ResponseBundle\Serializer\Normalizer\LinkNormalizer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->set(ApiResponseNormalizer::class)
            ->args([
                '$translator' => service(TranslatorInterface::class),
            ])
            ->tag('serializer.normalizer')

        ->set(ApiResponseErrorNormalizer::class)
            ->args([
                '$translator' => service(TranslatorInterface::class),
                '$nameConverter' => service(NameConverterInterface::class)->nullOnInvalid(),
            ])
            ->tag('serializer.normalizer')

        ->set(ApiResponseFactory::class)
            ->args([
                '$serializer' => service(SerializerInterface::class),
                '$normalizer' => service(NormalizerInterface::class),
                '$requestStack' => service(RequestStack::class),
            ])

        ->set(LinkNormalizer::class)
            ->args([
                '$urlGenerator' => service(UrlGeneratorInterface::class),
            ])
            ->tag('serializer.normalizer')

        ->set(LinkCollectionNormalizer::class)
            ->tag('serializer.normalizer')
    ;
};
