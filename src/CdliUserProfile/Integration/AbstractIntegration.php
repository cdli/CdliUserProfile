<?php
namespace CdliUserProfile\Integration;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

abstract class AbstractIntegration implements ServiceLocatorAwareInterface, ListenerAggregateInterface
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
     * @var array
     */
    protected $fieldSettings;

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

    /**
     * Set the Field Settings array
     *
     * @param array $settings
     */
    public function setFieldSettings($settings)
    {
        $this->fieldSettings = $settings;
    }

    /**
     * Retrieve the injected Field Settings array
     *
     * @return array
     */
    public function getFieldSettings()
    {
        return $this->fieldSettings;
    }

}
