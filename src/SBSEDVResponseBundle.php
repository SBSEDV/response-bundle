<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle;

use SBSEDV\Bundle\FormBundle\SBSEDVFormBundle;
use SBSEDV\Bundle\ResponseBundle\DependencyInjection\Compiler\ExceptionListenerCompilerPass;
use SBSEDV\Bundle\ResponseBundle\EventListener\DefaultRequestFormatEventListener;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SBSEDVResponseBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definitions/*.php');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        if ($config['exception_normalizer'] === true) {
            /** @var array<string, string> $bundles */
            $bundles = $builder->getParameter('kernel.bundles');

            if (\in_array(SBSEDVFormBundle::class, $bundles, true)) {
                $container->import('../config/services/form_bundle.php');
            }

            $container->import('../config/services/exception_normalizer.php');
        }

        $container->import('../config/services/api_response.php');
        $container->import('../config/services/event_listeners.php');

        if ($config['event_listeners']['default_request_format']['enabled']) {
            $container->services()
                ->get(DefaultRequestFormatEventListener::class)
                ->arg('$defaultFormat', (string) $config['event_listeners']['default_request_format']['value'])
            ;
        } else {
            $container->services()->remove(DefaultRequestFormatEventListener::class);
        }
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ExceptionListenerCompilerPass());
    }
}
