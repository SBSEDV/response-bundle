<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SBSEDV\Bundle\ResponseBundle\EventListener\DefaultRequestFormatEventListener;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->defaults()
            ->tag('kernel.event_subscriber')

        ->set(DefaultRequestFormatEventListener::class)
            ->args([
                '$isDebug' => '%kernel.debug%',
                '$defaultFormat' => abstract_arg('The configured default request format.'),
            ])

    ;
};
