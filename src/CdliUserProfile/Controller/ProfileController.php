<?php

namespace CdliUserProfile\Controller;

use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;

class ProfileController extends AbstractActionController
{
    protected $profileService;
    protected $moduleOptions;

    /**
     * User Profile Page
     */
    public function indexAction()
    {
        $messages = array();
        $service = $this->getProfileService();
        $service->setUser($this->getServiceLocator()->get('zfcuser_auth_service')->getIdentity());
        $sections = $service->getSections();

        if ($this->getRequest()->isPost()) 
        {
            $data = $this->getRequest()->getPost()->toArray();
            if ( $service->save($data) ) 
            {
                $messages[] = array(
                    'type'    => 'success',
                    'icon'    => 'icon-ok-sign',
                    'message' => 'Your profile has been updated successfully!',
                );
            }
            else
            {
                $messages[] = array(
                    'type'    => 'error',
                    'icon'    => 'icon-remove-sign',
                    'message' => 'Profile update failed!  See error messages below for more details.',
                );
            }
        }

        return new ViewModel(array(
            'messages'  => $messages,
            'user'      => $service->getUser(),
            'sections'  => $sections,
            'options'   => $this->getModuleOptions()
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
