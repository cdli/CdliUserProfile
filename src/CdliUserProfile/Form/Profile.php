<?php
namespace CdliUserProfile\Form;

use Zend\Form\Form;
use ZfcUser\Form\Register;
use CdliUserProfile\Module as modCUP;

class Profile extends Register
{
    public function __construct()
    {
        parent::__construct();
    }
}
