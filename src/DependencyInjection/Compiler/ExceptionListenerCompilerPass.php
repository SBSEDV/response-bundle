<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\DependencyInjection\Compiler;

use SBSEDV\Bundle\ResponseBundle\EventListener\ErrorListener;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ExceptionListenerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition('exception_listener')) {
            $container
                ->getDefinition('exception_listener')
                ->setClass(ErrorListener::class)
            ;
        }
    }
}
