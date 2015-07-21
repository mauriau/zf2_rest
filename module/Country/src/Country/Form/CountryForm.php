<?php

namespace Country\Form;

use Zend\Form\Form;

class CountryForm extends Form
{

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('country');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'code',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Code',
            ),
        ));
        $this->add(array(
            'name' => 'alpha2',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Alpha2',
            ),
        ));
        $this->add(array(
            'name' => 'alpha3',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Alpha3',
            ),
        ));
        $this->add(array(
            'name' => 'nomEnGb',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Nom En',
            ),
        ));
        $this->add(array(
            'name' => 'nomFrFr',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Nom Fr',
            ),
        ));
        $this->add(array(
            'name' => 'devise',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Devise',
            ),
        ));
        $this->add(array(
            'name' => 'tauxTva',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Tva',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }

}
