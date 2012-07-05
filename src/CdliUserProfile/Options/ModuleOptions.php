<?php
namespace CdliUserProfile\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    protected $fieldSettings;

    public function setFieldSettings($settings)
    {
        $this->fieldSettings = $settings;
        return $this;
    }

    public function getFieldSettings()
    {
        return $this->fieldSettings;
    }
}
