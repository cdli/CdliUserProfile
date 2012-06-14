<?php
return array(
    'cdli-user-profile' => array(
        'field-settings' => array(
            'zfcuser' => array(
                'username' => array(
                    'displayed' => true,
                    'editable'  => true,
                ),
                'email' => array(
                    'displayed' => true,
                    'editable'  => true,
                ),
                'display_name' => array(
                    'displayed' => true,
                    'editable'  => true,
                ),
                'password' => array(
                    'displayed' => true,
                    'editable'  => true,
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'cdliuserprofile' => __DIR__ . '/../view',
        ),
    ),
    'controller' => array(
        'classes' => array(
            'cdliuserprofile' => 'CdliUserProfile\Controller\ProfileController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'zfcuser' => array(
                'child_routes' => array(
                    'profile' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/profile',
                            'defaults' => array(
                                'controller' => 'cdliuserprofile',
                                'action'     => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
