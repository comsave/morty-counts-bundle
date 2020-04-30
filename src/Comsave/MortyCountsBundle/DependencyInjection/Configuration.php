<?php

namespace Comsave\MortyCountsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public static $configurationTreeRoot = 'comsave_morty_counts';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder
            ->root(static::$configurationTreeRoot)
            ->children()
                ->arrayNode('prometheus')
                    ->children()
                        ->scalarNode('host')
                            ->example('localhost:9090')
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
//                        ->arrayNode('jobs')
//                        ->end()
                    ->end()
                ->end()
                ->arrayNode('pushgateway')
                    ->children()
                        ->scalarNode('host')
                            ->example('localhost:9191')
                            ->isRequired()
                        ->end()
                        ->scalarNode('redis')
                            ->example('localhost:6379')
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
            ->end();

        return $treeBuilder;
    }
}