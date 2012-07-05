<?php
namespace CdliUserProfile\Model;

use Zend\Form\FormInterface;
use Zend\View\Model\ModelInterface;

interface ProfileSectionInterface
{
    public function setForm(FormInterface $form);
    public function getForm();

    public function setViewScript($script);
    public function getViewScript();

    public function setViewScriptFormKey($script);
    public function getViewScriptFormKey();

    public function setViewModel(ModelInterface $vm);
    public function getViewModel();

    public function setFieldSettings($settings);
    public function getFieldSettings();
}
