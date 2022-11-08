<?php declare(strict_types=1);

namespace Symfony\Component\Config\Definition\Configurator;

return function (DefinitionConfigurator $definition): void {
    $definition
        ->rootNode()
            ->children()
                ->booleanNode('exception_normalizer')->defaultTrue()->end()
            ->end()
        ->end()
    ;
};
