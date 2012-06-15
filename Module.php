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
        $profileEvents = $serviceManager->get('CdliUserProfile\Service\Profile')->events();
        $profileEvents->attachAggregate($serviceManager->get('CdliUserProfile\Integration\ZfcUser'));
    }

    public function getServiceConfiguration()
    {
        return array(
            'invokables' => array(
                'cdliuserprofile_section_zfcuser_form' => 'CdliUserProfile\Form\Section\ZfcUser',
            ),
            'factories' => array(
                'CdliUserProfile\Service\Profile' => function($sm) {
                    $obj = new Service\Profile();
                    return $obj;
                },
                'CdliUserProfile\Integration\ZfcUser' => function($sm) {
                    $obj = new Integration\ZfcUser();
                    $obj->setServiceLocator($sm);
                    return $obj;
                }
            )
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
        static::$options = $config['cdli-user-profile'];
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
