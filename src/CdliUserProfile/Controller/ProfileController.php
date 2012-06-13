<?php

namespace CdliUserProfile\Controller;

use Zend\Form\Form;
use Zend\Mvc\Controller\ActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;
use CdliUserProfile\Module as modCUP;

class ProfileController extends ActionController
{
    /**
     * User page 
     */
    public function indexAction()
    {
        return new ViewModel();
    }
}
