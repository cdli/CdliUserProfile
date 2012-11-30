<?php
namespace CdliUserProfile\Service;

use ZfcBase\EventManager\EventProvider;
use CdliUserProfile\Model\ProfileSectionInterface;
use CdliUserProfile\Options\ModuleOptions;

class Profile extends EventProvider
{
    protected $sections = NULL;
    protected $fieldSettings;
    protected $user;

    public function __construct(ModuleOptions $options)
    {
        $this->fieldSettings = $options->getFieldSettings();
    }

    public function addSection($name, ProfileSectionInterface $model)
    {
        $model->setFieldSettings(isset($this->fieldSettings[$name])
            ? $this->fieldSettings[$name] : array()
        );
        $this->sections[$name] = $model;
        return $this;
    }

    public function getSections()
    {
        if ($this->sections === null) {
            $this->sections = array();
            $this->getEventManager()->trigger(__FUNCTION__, $this);
        }
        return $this->sections;
    }

    public function getSection($key)
    {
        if ($this->sections === null) {
            $this->getSections();
        }
        return $this->sections[$key];
    }

    public function save($data)
    {
        $results = $this->getEventManager()->trigger(__FUNCTION__, $this, array('data'=>$data));
        return ( $results->contains(false) === false );
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }
}
