<?php
namespace CdliUserProfile\Form\Section;

use ZfcUser\Form\Base as BaseForm;
use ZfcUser\Options\RegistrationOptionsInterface;

class ZfcUser extends BaseForm
{
    public function __construct(RegistrationOptionsInterface $opt)
    {
        $this->setRegistrationOptions($opt);

        parent::__construct();
        $this->remove('submit');
        $this->remove('csrf');
    }

    /**
     * Set Regsitration Options
     *
     * @param RegistrationOptionsInterface $registrationOptions
     * @return Register
     */
    public function setRegistrationOptions(RegistrationOptionsInterface $registrationOptions)
    {
        $this->registrationOptions = $registrationOptions;
        return $this;
    }

    /**
     * Get Regsitration Options
     *
     * @return RegistrationOptionsInterface
     */
    public function getRegistrationOptions()
    {
        return $this->registrationOptions;
    }
}
