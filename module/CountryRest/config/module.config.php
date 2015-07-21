<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CountryRest\Controller\CountryRest' => 'CountryRest\Controller\CountryRestController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'rest' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/api/country[/:code]',
                    'defaults' => array(
                        'controller' => 'CountryRest\Controller\CountryRest',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);