<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 22.04.20
 * Time: 17:32
 */

namespace HelperManager\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class HelperManagerExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $exporterConfig = isset($config['helper']) ? $config['helper'] : array();
        $container->setParameter('helper.exporter_config', $exporterConfig);
    }
}
