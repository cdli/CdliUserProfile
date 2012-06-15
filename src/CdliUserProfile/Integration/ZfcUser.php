<?php
namespace CdliUserProfile\Integration;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use CdliUserProfile\Model\ProfileSection;
use CdliUserProfile\Module as modCUP;
use ZfcUser\Util\Password;

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
        $this->listeners[] = $events->attach('save', array($this, 'save'));
    }

    public function save(EventInterface $e)
    {
        $config = modCUP::getOption('field-settings');
        $section = $e->getTarget()->getSection('zfcuser');
        $data = $e->getParam('data');
        $user = $this->getServiceLocator()->get('zfcuser_auth_service')->getIdentity();
        $form = $section->getForm();
        
        // Determine which fields should be validated
        $enabledFields = array();
        foreach ($config['zfcuser'] as $fname=>$fsetting) {
            if ($fsetting['displayed'] && $fsetting['editable']) {
                $enabledFields[] = $fname;
            }
        }
        // Drop the password fields if they are empty
        if (empty($data['password']) || empty($data['passwordVerify'])) {
            $enabledFields = array_diff($enabledFields, array('password','passwordVerify'));
        }

        // Populate the form object
        $form->setValidationGroup($enabledFields);
        $form->bind($user);
        $form->setData($data);

        // If it validates...
        if ($form->isValid()) {
            // Pull out updated user object...
            $user = $form->getData();
            $user->setPassword(Password::hash($user->getPassword()));

            //...and persist it
            $mapper = $this->getServiceLocator()->get('zfcuser_user_mapper');
            $mapper->persist($user);
        }
    }

    /**
     * Add ZfcUser form section to CdliUserProfile
     * 
     * @param EventInterface $e event
     */
    public function addFormSection(EventInterface $e)
    {
        // Pull the form
        $form = $this->getServiceLocator()->get('CdliUserProfile\Form\Section\ZfcUser');

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

