<?php
namespace CdliUserProfile\Model;

use Zend\Form\FormInterface;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;

class ProfileSection implements ProfileSectionInterface
{
    protected $form;
    protected $viewScript;
    protected $viewScriptFormKey;
    protected $viewModel;
    protected $fieldSettings;

    public function setForm(FormInterface $form)
    {
        $this->form = $form;
        return $this;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function setViewScript($script)
    {
        $this->viewScript = $script;
        return $this;
    }

    public function getViewScript()
    {
        return $this->viewScript;
    }

    public function setViewScriptFormKey($key)
    {
        $this->viewScriptFormKey = $key;
        return $this;
    }

    public function getViewScriptFormKey()
    {
        return $this->viewScriptFormKey;
    }

    public function setViewModel(ModelInterface $vm)
    {
        $this->viewModel = $vm;
        return $this;
    }

    public function getViewModel()
    {
        if (null === $this->viewModel) {
            $this->viewModel = new ViewModel(array(
                $this->getViewScriptFormKey() => $this->getForm()
            ));
            $this->viewModel->setTemplate($this->getViewScript());
        }
        return $this->viewModel;
    }

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
