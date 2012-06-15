<?php
namespace CdliUserProfile\Form\Section;

use Zend\InputFilter\InputFilter;

class ZfcUserFilter extends InputFilter
{
    public function __construct($uemail, $uusername)
    {
        $this->add(array(
            'name'          => 'username',
            'required'      => true,
            'validators'    => array(
                array(
                    'name'      => 'StringLength',
                    'options'   => array(
                        'min'   => 3,
                        'max'   => 255,
                    ),
                ),
                $uusername
            ),
        ));

        $this->add(array(
            'name'          => 'email',
            'required'      => true,
            'validators'    => array(
                array(
                    'name'      => 'EmailAddress',
                ),
                $uemail
            ),
        ));

        $this->add(array(
            'name'          => 'display_name',
            'required'      => true,
                'filters'       => array(array('name' => 'StringTrim')),
                'validators'    => array(
                    array(
                        'name'      => 'StringLength',
                        'options'   => array(
                        'min'   => 3,
                        'max'   => 128,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'          => 'password',
            'required'      => true,
            'filters'       => array(array('name' => 'StringTrim')),
            'validators'    => array(
                array(
                    'name'      => 'StringLength',
                    'options'   => array(
                        'min'   => 6,
                        'max'   => 128,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'          => 'passwordVerify',
            'required'      => true,
            'filters'       => array(array('name' => 'StringTrim')),
            'validators'    => array(
                array(
                    'name'      => 'StringLength',
                    'options'   => array(
                       'min'   => 6,
                       'max'   => 128,
                    ),
                ),
                array(
                    'name'      => 'Identical',
                    'options'   => array(
                        'token' => 'password',
                    ),
                ),
            ),
        ));
    }
}
