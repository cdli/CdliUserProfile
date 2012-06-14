<?php

namespace CdliUserProfile\Controller;

use Zend\Form\Form;
use Zend\Mvc\Controller\ActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;
use CdliUserProfile\Module as modCUP;

class ProfileController extends ActionController
{
    protected $profileService;

    /**
     * User Profile Page
     */
    public function indexAction()
    {
        $service = $this->getProfileService();
        return new ViewModel(array(
            'sections' => $service->getSections()
        ));
    }

    protected function getProfileService()
    {
        if ($this->profileService === null) {
            $this->profileService = $this->getServiceLocator()->get('CdliUserProfile\Service\Profile');
        }
        return $this->profileService;
    }
}
