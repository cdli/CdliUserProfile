<?php

namespace CdliUserProfile;

use Zend\ModuleManager\ModuleManager,
    Zend\EventManager\StaticEventManager,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\BootstrapListenerInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\EventManager\EventInterface;

class Module implements
    BootstrapListenerInterface,
    AutoloaderProviderInterface,
    ConfigProviderInterface
{
    public function onBootstrap(EventInterface $e)
    {
        $serviceManager = $e->getTarget()->getServiceManager();
        $profileEvents = $serviceManager->get('CdliUserProfile\Service\Profile')->getEventManager();
        $profileEvents->attachAggregate($serviceManager->get('CdliUserProfile\Integration\ZfcUser'));
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'cdliuserprofile_module_options' => function ($sm) {
                    $config = $sm->get('Configuration');
                    return new Options\ModuleOptions($config['cdli-user-profile']);
                },

                'CdliUserProfile\Service\Profile' => function($sm) {
                    $obj = new Service\Profile($sm->get('cdliuserprofile_module_options'));
                    return $obj;
                },
                'CdliUserProfile\Integration\ZfcUser' => function($sm) {
                    $obj = new Integration\ZfcUser();
                    $obj->setServiceLocator($sm);
                    return $obj;
                },
                'CdliUserProfile\Form\Section\ZfcUser' => function($sm) {
                    $obj = new Form\Section\ZfcUser($sm->get('zfcuser_module_options'));
                    $obj->setInputFilter($sm->get('CdliUserProfile\Form\Section\ZfcUserFilter'));
                    $obj->setHydrator($sm->get('zfcuser_user_hydrator'));
                    return $obj;
                },
                'CdliUserProfile\Form\Section\ZfcUserFilter' => function($sm) {
                    return new Form\Section\ZfcUserFilter(
                        $sm->get('cdliuserprofile_uemail_validator'),
                        $sm->get('cdliuserprofile_uusername_validator')
                    );
                },
                'cdliuserprofile_uemail_validator' => function($sm) {
                    $user = $sm->get('CdliUserProfile\Service\Profile')->getUser();
                    return new Validator\NoRecordExistsExceptIgnored(array(
                        'ignored_record_ids' => is_null($user) ? NULL : $user->getId(),
                        'mapper'             => $sm->get('zfcuser_user_mapper'),
                        'key'                => 'email'
                    ));
                },
                'cdliuserprofile_uusername_validator' => function($sm) {
                    $user = $sm->get('CdliUserProfile\Service\Profile')->getUser();
                    return new Validator\NoRecordExistsExceptIgnored(array(
                        'ignored_record_ids' => is_null($user) ? NULL : $user->getId(),
                        'mapper'             => $sm->get('zfcuser_user_mapper'),
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

    public function getConfig($env = NULL)
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
