<?php namespace Utils\Factory;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Utils\View\Renderer\XmlRenderer;

class XmlRendererFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $xmlRenderer = new XmlRenderer();
        return $xmlRenderer;
    }
}