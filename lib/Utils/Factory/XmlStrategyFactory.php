<?php namespace Utils\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Utils\View\Strategy\XmlStrategy;

class XmlStrategyFactory implements FactoryInterface
{
    /**
     * Create and return the XML view strategy
     *
     * Retrieves the XMLRenderer service from the service locator, and
     * injects it into the constructor for the XML strategy.
     *
     * It then attaches the strategy to the View service, at a priority of 100.
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return JsonStrategy
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $xmlRenderer = $serviceLocator->get('ViewXmlRenderer');
        $xmlStrategy = new XmlStrategy($xmlRenderer);

        return $xmlStrategy;
    }
}