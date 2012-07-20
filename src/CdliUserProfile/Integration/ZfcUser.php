<?php
namespace CdliUserProfile\Integration;

use Zend\EventManager\EventInterface;
use CdliUserProfile\Model\ProfileSection;
use Zend\Crypt\Password\Bcrypt;

class ZfcUser extends AbstractIntegration implements IntegrationInterface
{

    public function save(EventInterface $e)
    {
        $section = $e->getTarget()->getSection('zfcuser');
        $fieldSettings = $section->getFieldSettings();
        $data = $e->getParam('data');
        $user = $this->getProfileService()->getUser();
        $form = $section->getForm();
        
        // Determine which fields should be validated
        $enabledFields = array();
        foreach ($fieldSettings as $fname=>$fsetting) {
            if ($fsetting['displayed'] && $fsetting['editable']) {
                $enabledFields[] = $fname;
            }
        }
        // Drop the password fields if they are empty
        if (empty($data['password']) || empty($data['passwordVerify'])) {
            $enabledFields = array_diff($enabledFields, array('password','passwordVerify'));
        }

        // Populate the form object
        $form->setValidationGroup($enabledFields);
        $form->bind($user);
        $form->setData($data);

        // If it validates...
        if ($form->isValid()) {
            // Pull out updated user object...
            $user = $form->getData();

            // If they've changed the password, hash it
            if (in_array('password', $enabledFields)) {
                $moduleOptions = $this->getServiceLocator()->get('zfcuser_module_options');
                $bcrypt = new Bcrypt;
                $bcrypt->setCost($moduleOptions->getPasswordCost());
                $user->setPassword($bcrypt->create($user->getPassword()));
            }

            //...and persist it
            $mapper = $this->getServiceLocator()->get('zfcuser_user_mapper');
            $mapper->update($user);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Add ZfcUser form section to CdliUserProfile
     * 
     * @param EventInterface $e event
     */
    public function addFormSection(EventInterface $e)
    {
        // Pull the form
        $form = $this->getServiceLocator()->get('CdliUserProfile\Form\Section\ZfcUser');

        // Get User Account details
        $user = $this->getProfileService()->getUser();
        $userHydrator = $this->getServiceLocator()->get('zfcuser_user_hydrator');
        $userData = $userHydrator->extract($user);
        unset($userData['password']);

        $form->setData($userData);

        $obj = new ProfileSection();
        $obj->setForm($form);
        $obj->setViewScript('cdli-user-profile/profile/section/zfcuser');
        $obj->setViewScriptFormKey('registerForm');
        $e->getTarget()->addSection('zfcuser', $obj);
    }

}

