<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SBSEDV\Bundle\ResponseBundle\EventListener\InvalidFormExceptionEventListener;
use SBSEDV\Bundle\ResponseBundle\Response\ApiResponseFactory;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->set(InvalidFormExceptionEventListener::class)
            ->args([
                '$normalizer' => service(NormalizerInterface::class),
                '$apiResponseFactory' => service(ApiResponseFactory::class),
            ])
            ->tag('kernel.event_subscriber')

    ;
};
