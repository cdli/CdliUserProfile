<?php
return array(
    'cdli-user-profile' => array(
        'field_settings' => array(
            'zfcuser' => array(
                'username' => array(
                    'displayed' => false,
                    'editable'  => false,
                ),
                'email' => array(
                    'displayed' => true,
                    'editable'  => true,
                ),
                'display_name' => array(
                    'displayed' => false,
                    'editable'  => false,
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
    'controllers' => array(
        'invokables' => array(
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
