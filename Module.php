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
            'factories' => array(
                'CdliUserProfile\Service\Profile' => function($sm) {
                    $obj = new Service\Profile();
                    return $obj;
                },
                'CdliUserProfile\Integration\ZfcUser' => function($sm) {
                    $obj = new Integration\ZfcUser();
                    $obj->setServiceLocator($sm);
                    return $obj;
                },
                'CdliUserProfile\Form\Section\ZfcUser' => function($sm) {
                    $obj = new Form\Section\ZfcUser();
                    $obj->setInputFilter($sm->get('CdliUserProfile\Form\Section\ZfcUserFilter'));
                    $obj->setHydrator($sm->get('zfcuser_user_hydrator'));
                    return $obj;
                },
                'CdliUserProfile\Form\Section\ZfcUserFilter' => function($sm) {
                    return new Form\Section\ZfcUserFilter(
                        $sm->get('cdliuseraccount_profile_uemail_validator'),
                        $sm->get('cdliuseraccount_profile_uusername_validator')
                    );
                },
                'cdliuseraccount_profile_uemail_validator' => function($sm) {
                    $repository = $sm->get('zfcuser_user_repository');
                    $user = $sm->get('zfcuser_auth_service')->getIdentity();
                    return new Validator\NoRecordExistsExceptIgnored(array(
                        'ignored_record_ids' => is_null($user) ? NULL : $user->getUserId(),
                        'repository'         => $repository,
                        'key'                => 'email'
                    ));
                },
                'cdliuseraccount_profile_uusername_validator' => function($sm) {
                    $repository = $sm->get('zfcuser_user_repository');
                    $user = $sm->get('zfcuser_auth_service')->getIdentity();
                    return new Validator\NoRecordExistsExceptIgnored(array(
                        'ignored_record_ids' => is_null($user) ? NULL : $user->getUserId(),
                        'repository'         => $repository,
                        'key'                => 'username'
                    ));
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
