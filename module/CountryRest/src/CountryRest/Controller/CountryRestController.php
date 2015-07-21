<?php

//namespace CountryRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Country\Model\Country;
use Country\Form\CountryForm;
use Zend\View\Model\JsonModel;
use Zend\EventManager\EventManagerInterface;

class CountryRestController extends AbstractRestfulController
{

    protected $countryTable;
    protected $collectionMethod = ['GET'];
    protected $ressourceMethod = ['GET', 'POST', 'PATCH', 'DELETE'];

    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);
        $events->attach('dispatch', array($this, 'checkMethod'), 10);
    }

    protected function _getMethod()
    {
        if ($this->params()->fromRoute('slug', false)) {
            return $this->ressourceMethod;
        }
        return $this->collectionMethod;
    }

    public function checkMethod($e)
    {
        if (in_array($e->getRequest()->getMethod(), $this->_getMethod())) {
            return;
        }
        $response = $this->getResponse();
        $response->setStatusCode(405);
        return $response;
    }

    public function options()
    {
        $response = $this->getResponse();
        $response->getHeaders()
                ->addHeaderLine('Allow', implode(',', $this->_getMethod()));
        return $response;
    }

//    public function getList()
//    {
//        $results = $this->getCountryTable()->fetchAll();
//        $data = array();
//        foreach ($results as $result) {
//            $data[] = $result;
//        }
//
//        return new JsonModel(array(
//            'data' => $data,
//        ));
//    }

    public function getList()
    {
        // On récupère les paramètres de la query
//        $params = $this->params()->fromQuery();
        // Si on ajoute le paramètre 'year', on peut filtrer par année
//        if (isset($params['year'])
        // On filtre la requête par année
        $countrys = $this->getCountryTable()->fetchAll();
        return new JsonModel(array(
            'data' => $countrys,
        ));
    }

    public function get($id)
    {
        var_dump($id);
        $country = $this->getCountryTable()->getCountry($id);

        return new JsonModel(array(
            'data' => $country,
        ));
    }

    public function create($data)
    {
        $form = new CountryForm();
        $country = new Country();
        $form->setInputFilter($country->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            $country->exchangeArray($form->getData());
            $id = $this->getCountryTable()->saveCountry($country);
        }

        return $this->get($id);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $country = $this->getCountryTable()->getCountry($id);
        $form = new CountryForm();
        $form->bind($country);
        $form->setInputFilter($country->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            $id = $this->getCountryTable()->saveCountry($form->getData());
        }

        return $this->get($id);
    }

    public function delete($id)
    {
        $this->getCountryTable()->deleteCountry($id);

        return new JsonModel(array(
            'data' => 'deleted',
        ));
    }

    public function getCountryTable()
    {
        if (!$this->countryTable) {
            $sm = $this->getServiceLocator();
            $this->countryTable = $sm->get('Country\Model\CountryTable');
        }
        var_dump($this->countryTable);
        return $this->countryTable;
    }

}
