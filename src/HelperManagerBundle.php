<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 22.04.20
 * Time: 17:27
 */

namespace HelperManager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class HelperManagerBundle
 * @package HelperManager
 */
class HelperManagerBundle extends Bundle
{
    private static $containerInstance = null;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        self::$containerInstance = $container;
    }

    /**
     * @return ContainerInterface
     */
    public static function getContainer()
    {
        return self::$containerInstance;
    }
}