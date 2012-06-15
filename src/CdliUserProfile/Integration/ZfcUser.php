<?php
namespace CdliUserProfile\Integration;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use CdliUserProfile\Model\ProfileSection;

class ZfcUser implements ServiceLocatorAwareInterface, ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * @var ServiceLocatorInterface
     */
    protected $locator;
 
    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     * @param null|int $priority Optional priority "hint" to use when attaching listeners
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('getSections', array($this, 'addFormSection'));
    }

    /**
     * Add ZfcUser form section to CdliUserProfile
     * 
     * @param EventInterface $e event
     */
    public function addFormSection(EventInterface $e)
    {
        // Pull the form
        $form = $this->getServiceLocator()->get('cdliuserprofile_section_zfcuser_form');

        // Get User Account details
        $userData = $this->getServiceLocator()->get('zfcuser_auth_service')->getIdentity()->toArray();
        unset($userData['password']);
        $form->setData($userData);

        $obj = new ProfileSection();
        $obj->setForm($form);
        $obj->setViewScript('cdli-user-profile/profile/section/zfcuser');
        $obj->setViewScriptFormKey('registerForm');
        $e->getTarget()->addSection('zfcuser', $obj);
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
 
    /**
     * Set the Service Locator instance
     *
     * @param ServiceLocatorInterface $locator
     */
    public function setServiceLocator(ServiceLocatorInterface $locator) 
    {
        $this->locator = $locator;
    }
    
    /**
     * Retrieve the injected Service Locator instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->locator;
    }

}

