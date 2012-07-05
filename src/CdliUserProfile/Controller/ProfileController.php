<?php

namespace CdliUserProfile\Controller;

use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;
use CdliUserProfile\Module as modCUP;

class ProfileController extends AbstractActionController
{
    protected $profileService;
    protected $moduleOptions;

    /**
     * User Profile Page
     */
    public function indexAction()
    {
        $service = $this->getProfileService();
        $sections = $service->getSections();

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $service->save($data);
        }

        return new ViewModel(array(
            'sections'      => $sections,
            'fieldSettings' => $this->getModuleOptions()->getFieldSettings()
        ));
    }

    protected function getProfileService()
    {
        if ($this->profileService === null) {
            $this->profileService = $this->getServiceLocator()->get('CdliUserProfile\Service\Profile');
        }
        return $this->profileService;
    }

    protected function getModuleOptions()
    {
        if ($this->moduleOptions === null) {
            $this->moduleOptions = $this->getServiceLocator()->get('cdliuserprofile_module_options');
        }
        return $this->moduleOptions;
    }
}
