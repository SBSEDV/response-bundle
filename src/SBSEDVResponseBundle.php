<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle;

use SBSEDV\Bundle\ResponseBundle\DependencyInjection\Compiler\ExceptionListenerCompilerPass;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SBSEDVResponseBundle extends AbstractBundle
{
    /**
     * {@inheritdoc}
     */
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definitions/*.php');
    }
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ExceptionListenerCompilerPass());
    }
}
