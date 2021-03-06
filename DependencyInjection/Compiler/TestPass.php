<?php

/*
 * This file is part of the SnideMonitor bundle.
 *
 * (c) Pascal DENIS <pascal.denis.75@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Snide\Bundle\MonitorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class TestPass
 *
 * @author Pascal DENIS <pascal.denis.75@gmail.com>
 */
class TestPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // Get tagged services
        $testsServices = $container->findTaggedServiceIds(
            'snide_monitor.test'
        );

        $definition = $container->getDefinition(
            'snide_monitor.test_manager'
        );

        // Create list of tests
        if (is_array($testsServices)) {
            foreach (array_keys($testsServices) as $id) {
                $test = $container->get($id);

                if (!is_subclass_of($test, 'Snide\Monitoring\Model\Test')) {
                    throw \Exception(
                        'Service %s is not a Test class.
                         You must extends Snide\Monitoring\Model\Test class',
                        $id
                    );
                }
                // Add test to the test manager
                $definition->addMethodCall(
                    'addTest',
                    array(new Reference($id))
                );
            }
        }
    }
}
