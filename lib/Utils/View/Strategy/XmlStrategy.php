<?php namespace Utils\View\Strategy;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\View\ViewEvent;

use Utils\View\Model;


class XmlStrategy extends AbstractListenerAggregate
{
    protected $charset = 'utf-8';

    /**
     * @var XmlRenderer
     */
    protected $renderer;

    public function __construct($renderer = null)
    {
        $this->renderer = $renderer;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RENDERER, array($this, 'selectRenderer'), $priority);
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RESPONSE, array($this, 'injectResponse'), $priority);
    }

    public function setCharset($charset)
    {
        $this->charset = (string) $charset;
        return $this;
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function selectRenderer(ViewEvent $e)
    {
        $model = $e->getModel();

        if (!$model instanceof Model\XmlModel) {
            return;
        }

        return $this->renderer;
    }


    public function injectResponse(ViewEvent $e)
    {
        $renderer = $e->getRenderer();

        if ($renderer !== $this->renderer) {
            // Discovered renderer is not ours; do nothing
            return;
        }

        $result   = $e->getResult();

        if (!is_string($result)) {
            // We don't have a string, and thus, no XML
            return;
        }

        // Populate response
        $response = $e->getResponse();
        $response->setContent($result);
        $response->getHeaders()->addHeaderLine('Content-Type', 'text/xml; charset='.$this->charset);
    }
}