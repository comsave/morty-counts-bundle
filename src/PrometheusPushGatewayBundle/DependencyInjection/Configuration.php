<?php

namespace Comsave\PrometheusPushGatewayBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public static $configurationTreeRoot = 'comsave_prometheus_pushgateway';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder
            ->root(static::$configurationTreeRoot)
            ->children()
                ->arrayNode('prometheus')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('host')
                            ->defaultValue('localhost:9090')
                            ->isRequired()
                        ->end()
                        ->scalarNode('username')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('password')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('instance')
                            ->defaultValue('localhost')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('pushgateway')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('host')
                            ->defaultValue('localhost:9191')
                            ->isRequired()
                        ->end()
                        ->scalarNode('redis')
                            ->defaultValue('localhost:6379')
                            ->isRequired()
                        ->end()
                        ->scalarNode('username')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('password')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('metrics')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('type')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('help')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('prefetch_group_label')
                                ->defaultNull()
                            ->end()
                            ->arrayNode('labels')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}