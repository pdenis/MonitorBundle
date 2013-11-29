<?php

namespace Snide\Bundle\MonitorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @author Pascal DENIS <pascal.denis.75@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('snide_monitor');
        $rootNode
            ->children()
                ->scalarNode('timer')
                    ->defaultValue(60)
                ->end()
                ->arrayNode('repository')
                    ->children()
                        ->scalarNode('type')->isRequired()->end()
                        ->arrayNode('application')
                        ->children()
                            ->scalarNode('filename')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}