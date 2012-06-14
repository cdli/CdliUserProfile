<?php
namespace CdliUserProfile\Form\Section;

use ZfcUser\Form\Base as BaseForm;

class ZfcUser extends BaseForm
{
    public function __construct()
    {
        parent::__construct();
        $this->remove('submit');
        $this->remove('csrf');
    }
}
