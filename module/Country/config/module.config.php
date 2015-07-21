<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Country\Controller\Country' => 'Country\Controller\CountryController',
            'Country\Controller\CountryRest' => 'Country\Controller\CountryRestController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'country' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/country[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Country\Controller\Country',
                        'action' => 'index',
                    ),
                ),
            ),
            'rest' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/country[/:id]',
                    'defaults' => array(
                        'controller' => 'Country\Controller\CountryRest',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
            'ViewXmlStrategy' => 'Utils\View\Strategy\XmlStrategy',
            'ViewXmlRenderer' => 'Utils\View\Renderer\XmlRenderer'
        ),
        'factories' => array(
            'Utils\View\Strategy\XmlStrategy' => 'Utils\Factory\XmlStrategyFactory',
            'Utils\View\Renderer\XmlRenderer' => 'Utils\Factory\XmlRendererFactory'
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
            'ViewXmlStrategy'
        ),
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'template_path_stack' => array(
            'country' => __DIR__ . '/../view',
        ),
    ),
);
