<?php

namespace CdliUserProfile;

use Zend\ModuleManager\ModuleManager,
    Zend\EventManager\StaticEventManager,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\BootstrapListenerInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\EventManager\Event;

class Module implements
    BootstrapListenerInterface,
    AutoloaderProviderInterface,
    ConfigProviderInterface
{
    protected static $options;
    public function init(ModuleManager $moduleManager)
    {
        $moduleManager->events()->attach('loadModules.post', array($this, 'modulesLoaded'));
    }

    public function onBootstrap(Event $e)
    {
        $serviceManager = $e->getTarget()->getServiceManager();
    }

    public function getServiceConfiguration()
    {
        return array(
            'factories' => array()
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function modulesLoaded($e)
    {
        $config = $e->getConfigListener()->getMergedConfig(false);
        static::$options = $config['psaccess-account'];
    }

    /**
     * @TODO: Come up with a better way of handling module settings/options
     */
    public static function getOption($option)
    {
        if (!isset(static::$options[$option])) {
            return null;
        }
        return static::$options[$option];
    }

    public function getConfig($env = NULL)
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
