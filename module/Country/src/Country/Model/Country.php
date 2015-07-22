<?php

namespace Country\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Country implements InputFilterAwareInterface
{

    /**
     * @var integer
     *
     */
    public $id;

    /**
     * @var integer
     *
     */
    public $code;

    /**
     * @var string
     *
     */
    public $alpha2;

    /**
     * @var string
     *
     */
    public $alpha3;

    /**
     * @var string
     *
     */
    public $nomEnGb;

    /**
     * @var string
     *
     */
    public $nomFrFr;

    /**
     * @var string
     *
     */
    public $devise;

    /**
     * @var string
     *
     */
    public $tauxTva;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? (int) $data['id'] : null;
        $this->alpha2 = (isset($data['alpha2'])) ? $data['alpha2'] : null;
        $this->alpha3 = (isset($data['alpha3'])) ? $data['alpha3'] : null;
        $this->code = (isset($data['code'])) ? (int) $data['code'] : null;
        $this->devise = (isset($data['devise'])) ? $data['devise'] : null;
        $this->nomEnGb = (isset($data['nomEnGb'])) ? $data['nomEnGb'] : null;
        $this->nomFrFr = (isset($data['nomFrFr'])) ? $data['nomFrFr'] : null;
        $this->tauxTva = (isset($data['tauxTva'])) ? $data['tauxTva'] : null;
    }

    public function toArray()
    {
        return Array(
            "id" => $this->id,
            "alpha2" => utf8_encode($this->alpha2),
            "alpha3" => utf8_encode($this->alpha3),
            "code" => $this->code,
            "devise" => utf8_encode($this->devise),
            "nomEnGb" => utf8_encode($this->nomEnGb),
            "nomFrFr" => utf8_encode($this->nomFrFr),
            "tauxTva" => $this->tauxTva
        );
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                        'name' => 'id',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'code',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));
            $inputFilter->add($factory->createInput(array(
                        'name' => 'alpha2',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 2,
                                    'max' => 2,
                                ),
                            ),
                        ),
            )));
            $inputFilter->add($factory->createInput(array(
                        'name' => 'alpha3',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 3,
                                    'max' => 3,
                                ),
                            ),
                        ),
            )));
            $inputFilter->add($factory->createInput(array(
                        'name' => 'nomEnGb',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 1,
                                    'max' => 100,
                                ),
                            ),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'nomFrFr',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 1,
                                    'max' => 100,
                                ),
                            ),
                        ),
            )));
            $inputFilter->add($factory->createInput(array(
                        'name' => 'devise',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 1,
                                    'max' => 50,
                                ),
                            ),
                        ),
            )));
            $inputFilter->add($factory->createInput(array(
                        'name' => 'tauxTva',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 1,
                                    'max' => 50,
                                ),
                            ),
                        ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    function getId()
    {
        return $this->id;
    }

    function getCode()
    {
        return $this->code;
    }

    function getAlpha2()
    {
        return $this->alpha2;
    }

    function getAlpha3()
    {
        return $this->alpha3;
    }

    function getNomEnGb()
    {
        return $this->nomEnGb;
    }

    function getNomFrFr()
    {
        return $this->nomFrFr;
    }

    function getDevise()
    {
        return $this->devise;
    }

    function getTauxTva()
    {
        return $this->tauxTva;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setCode($code)
    {
        $this->code = $code;
    }

    function setAlpha2($alpha2)
    {
        $this->alpha2 = $alpha2;
    }

    function setAlpha3($alpha3)
    {
        $this->alpha3 = $alpha3;
    }

    function setNomEnGb($nomEnGb)
    {
        $this->nomEnGb = $nomEnGb;
    }

    function setNomFrFr($nomFrFr)
    {
        $this->nomFrFr = $nomFrFr;
    }

    function setDevise($devise)
    {
        $this->devise = $devise;
    }

    function setTauxTva($tauxTva)
    {
        $this->tauxTva = $tauxTva;
    }

}
