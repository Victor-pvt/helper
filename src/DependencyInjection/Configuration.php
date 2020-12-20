<?php

/**
 * Created by PhpStorm.
 * User: victor
 * Date: 22.04.20
 * Time: 17:31
 */

namespace HelperManager\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('helper');
        $rootNode
            ->children()
            ->scalarNode('logger')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
