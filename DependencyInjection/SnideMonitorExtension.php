<?php

namespace Snide\Bundle\MonitorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

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

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('model.xml');
        $loader->load('form.xml');
        $loader->load('executor.xml');
        $loader->load('manager.xml');


        if(isset($config['repository']['type'])) {
            $this->loadRepository($loader, $container, $config['repository']);

        }else {
            throw new \Exception('You must define repository type parameter');
        }
    }

    /**
     * Load repository
     *
     * @param XmlFileLoader $loader
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array $repository Repository parameters
     * @throws \Exception
     */
    protected function loadRepository($loader, ContainerBuilder $container, array $repository)
    {
        if($repository['type'] == 'yaml') {
            if(!isset($repository['application']['filename'])) {
                throw new \Exception('You must define filename parameter for application yaml repository');
            }
            $container->setParameter('snide_monitor.application_repository.filename', $repository['application']['filename']);
        }

        $loader->load('repository/'.$repository['type'].'.xml');
    }
}
