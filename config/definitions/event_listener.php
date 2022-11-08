<?php declare(strict_types=1);

namespace Symfony\Component\Config\Definition\Configurator;

return function (DefinitionConfigurator $definition): void {
    $definition
        ->rootNode()
            ->children()
                ->arrayNode('event_listeners')
                    ->addDefaultsIfNotSet()
                    ->info('Configure custom event listeners')
                    ->children()
                        ->arrayNode('default_request_format')
                            ->info('Automatically set the default request format.')
                            ->treatTrueLike(['enabled' => true])
                            ->treatFalseLike(['enabled' => false])
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultTrue()->end()
                                ->scalarNode('value')
                                    ->cannotBeEmpty()
                                    ->defaultValue('json')
                                ->end()
                            ->end()
                        ->end() // default_request_format
                    ->end()
                ->end()
            ->end()
        ->end()
    ;
};
