<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 05.03.19
 * Time: 15:22
 */

namespace HelperManager\Helper;

use HelperManager\HelperManagerBundle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LogHelper
{
    const PATH_LOGS = 'var/log';
    protected static $logName = null;
    /** @var LogHelper */
    private static $instance = null;
    /** @var  string */
    protected static $logDir;
    /** @var  ContainerInterface */
    protected static $container;
    /** @var  string */
    protected static $fileName;
    /** @var  string */
    protected static $prefix;

    /**
     * @param string $fileName
     * @return LogHelper
     */
    public static function getInstance($fileName = 'default', $timeFormat = 'd')
    {
        if (static::$instance === null) {
            static::$instance = new LogHelper();
        }
        $container = static::getContainer();
        $logDir = realpath($container->getParameter('kernel.project_dir')) . '/' . self::PATH_LOGS;
        $dateLog = DateTimeHelper::getDateStringLog($timeFormat);
        if (static::$logName === null) {
            static::$prefix = $logDir . '/' . $dateLog;
            static::$logName = static::$prefix . '-' . $fileName . '.log';
            static::$fileName = $fileName;
        } else {
            if (static::$fileName != $fileName) {
                static::$prefix = $logDir . '/' . $dateLog;
                static::$logName = static::$prefix . '-' . $fileName . '.log';
                static::$fileName = $fileName;
            }
        }

        return static::$instance;
    }

    /**
     * @param $messages
     */
    public function setStr($messages)
    {
        $messages = DateTimeHelper::getDateString() . ': ' . $messages . PHP_EOL;
        file_put_contents(static::$logName, $messages, FILE_APPEND);
    }

    /**
     * @return ContainerInterface
     */
    public static function getContainer()
    {
        if (!static::$container) {
            static::$container = HelperManagerBundle::getContainer();
        }

        return static::$container;
    }
}