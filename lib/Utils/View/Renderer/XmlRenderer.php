<?php namespace Utils\View\Renderer;

use Zend\View\Exception;
use Utils\View\Model\XmlModel;
use Zend\View\Model\ModelInterface as Model;
use Zend\View\Renderer\RendererInterface as Renderer;
use Zend\View\Resolver\ResolverInterface as Resolver;

/**
 * XML renderer
 */
class XmlRenderer implements Renderer
{
    /**
     * @var Resolver
     */
    protected $resolver;


    public function getEngine()
    {
        return $this;
    }

    public function setResolver(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }


    public function render($nameOrModel, $values = null)
    {
        if ($nameOrModel instanceof Model && $nameOrModel instanceof XmlModel) {
            return $nameOrModel->serialize();
        }

        // Both $nameOrModel and $values are populated
        throw new Exception\DomainException(sprintf(
            '%s: Do not know how to handle operation when both $nameOrModel and $values are populated',
            __METHOD__
        ));
    }
}