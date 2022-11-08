<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SBSEDV\Bundle\ResponseBundle\Serializer\Normalizer\HttpExceptionNormalizer;
use SBSEDV\Bundle\ResponseBundle\Serializer\Normalizer\ProblemNormalizer;
use Symfony\Contracts\Translation\TranslatorInterface;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->set(ProblemNormalizer::class)
            ->args([
                '$translator' => service(TranslatorInterface::class),
            ])
            ->tag('serializer.normalizer')

        ->set(HttpExceptionNormalizer::class)
            ->tag('serializer.normalizer')
    ;
};
