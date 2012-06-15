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
        $sections = $service->getSections();

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->post()->toArray();
            $service->save($data);
        }

        return new ViewModel(array(
            'sections' => $sections
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
