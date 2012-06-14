<?php
namespace CdliUserProfile\Service;

use ZfcBase\EventManager\EventProvider;
use CdliUserProfile\Model\ProfileSectionInterface;

class Profile extends EventProvider
{
    protected $sections = NULL;

    public function addSection($name, ProfileSectionInterface $model)
    {
        $this->sections[$name] = $model;
        return $this;
    }

    public function getSections()
    {
        if ($this->sections === null) {
            $this->sections = array();
            $this->events()->trigger(__FUNCTION__, $this);
        }
        return $this->sections;
    }
}
