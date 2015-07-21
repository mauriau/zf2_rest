<?php namespace Utils\View\Model;

use Zend\Http\Response;
use Zend\View\Model\ViewModel;


class XmlModel extends ViewModel
{
    protected $captureTo = null;

    private $xml      = null;
    private $encoding = 'UTF-8';
    private $version  = '1.0';
    private $formatOutput = true;

    /**
     * XML is usually terminal
     *
     * @var bool
     */
    protected $terminate = true;


    public function serialize()
    {
        $vars    = $this->getVariables();
        if ($vars instanceof Traversable) {
            $vars = ArrayUtils::iteratorToArray($vars);
        }
        $options = $this->getOptions();

        $key = key($vars);
        $xml = $this->createXML($key, $vars[$key]);

        return $xml->saveXML();
    }

    public function &createXML($nodeName, $array = array())
    {
        $xml = $this->getXMLRoot();
        $xml->appendChild($this->convert($nodeName, $array));

        $this->xml = null;

        return $xml;
    }

    private function getXMLRoot()
    {
        if (!$this->xml) {
            $this->init();
        }

        return $this->xml;
    }

    public function init()
    {
        $this->xml = new \DomDocument($this->version, $this->encoding);
        $this->xml->formatOutput = $this->format_output;
    }

    private function &convert($nodeName, $array = array())
    {

        $xml  = $this->getXMLRoot();
        $node = $xml->createElement($nodeName);

        if(is_array($array)) {
            if(isset($array['@attributes'])) {
                foreach($array['@attributes'] as $key => $value) {
                    if(!$this->isValidTagName($key)) {
                        throw new \Exception(
                            'Illegal character in attribute name. attribute: ' . $key . ' in node: ' . $nodeName
                        );
                    }
                    $node->setAttribute($key, $this->bool2str($value));
                }
                unset($array['@attributes']);
            }

            if(isset($array['@value'])) {
                $node->appendChild($xml->createTextNode($this->bool2str($array['@value'])));
                unset($array['@value']);

                return $node;
            } else {
                if(isset($array['@cdata'])) {
                    $node->appendChild($xml->createCDATASection($this->bool2str($array['@cdata'])));
                    unset($array['@cdata']);

                    return $node;
                }
            }
        }

        if(is_array($array)) {
            foreach($array as $key => $value) {
                if(!$this->isValidTagName($key)) {
                    throw new \Exception(
                        'Illegal character in tag name. tag: ' . $key . ' in node: ' . $nodeName
                    );
                }
                if(is_array($value) && is_numeric(key($value))) {
                    foreach($value as $k => $v) {
                        $node->appendChild($this->convert($key, $v));
                    }
                } else {
                    $node->appendChild($this->convert($key, $value));
                }
                unset($array[$key]);
            }
        }

        if(!is_array($array)) {
            $node->appendChild($xml->createTextNode($this->bool2str($array)));
        }

        return $node;
    }

    private function isValidTagName($tag)
    {
        $pattern = '/^[a-z_]+[a-z0-9\:\-\.\_]*[^:]*$/i';

        return preg_match($pattern, $tag, $matches) && $matches[0] == $tag;
    }

    private function bool2str($value)
    {
        $value = $value === true ? 'true' : $value;
        $value = $value === false ? 'false' : $value;

        return $value;
    }
}