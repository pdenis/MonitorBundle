<?php

namespace Snide\Bundle\MonitorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Classe SnideMonitorExtension
 *
 * @author Pascal DENIS <pascal.denis.75@gmail.com>
 */
class SnideMonitorExtension extends Extension
{
    /**
     * Load configuration of Bundle
     *
     * @param array $configs Configuration parameters
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('model.xml');
        $loader->load('form.xml');
        $loader->load('executor.xml');
        $loader->load('loader.xml');
        $loader->load('manager.xml');

        $this->loadRepository($loader, $container, $config);
        $this->loadTimer($loader, $container, $config);


    }

    /**
     * Load repository
     *
     * @param XmlFileLoader $loader
     * @param ContainerBuilder $container
     * @param array $config
     * @throws \Exception
     */
    protected function loadRepository($loader, ContainerBuilder $container, array $config)
    {
        if (isset($config['repository']['type'])) {
            if ($config['repository']['type'] == 'yaml') {
                if (!isset($config['repository']['application']['filename'])) {
                    throw new \Exception('You must define filename parameter for application yaml repository');
                }
                $container->setParameter(
                    'snide_monitor.application_repository.filename',
                    $config['repository']['application']['filename']
                );
            }

            $loader->load('repository/' . $config['repository']['type'] . '.xml');
        } else {
            throw new \Exception('You must define repository type parameter');
        }

    }

    /**
     * Load timer
     *
     * @param XmlFileLoader $loader
     * @param ContainerBuilder $container
     * @param array $config
     * @throws \Exception
     */
    protected function loadTimer($loader, ContainerBuilder $container, array $config)
    {
        if (isset($config['timer'])) {
            $container->setParameter(('snide_monitor.timer'), $config['timer']);
        }else {
            throw new \Exception('You must define timer parameter');
        }
    }
}
