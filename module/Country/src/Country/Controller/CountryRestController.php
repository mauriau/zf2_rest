<?php

namespace Country\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Country\Model\Country;
use Country\Form\CountryForm;
use Zend\View\Model\JsonModel;
use Utils\View\Model\XmlModel;
use Zend\EventManager\EventManagerInterface;

/**
 * url to try : 
 *  get => http://localhost:8000/api/country/8?fields=nomFrFr,alpha3
 */
class CountryRestController extends AbstractRestfulController
{

    protected $countryTable;
    protected $collectionMethod = ['GET', 'POST'];
    protected $ressourceMethod = ['GET', 'POST', 'PATCH', 'DELETE'];
    protected $acceptCriteria = [
        'Zend\View\Model\JsonModel' => [
            'application/json',
        ],
        'Utils\View\Model\XmlModel' => [
            'application/xml',
            'text/xml'
        ]
    ];

    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);
        $events->attach('dispatch', array($this, 'checkMethod'), 10);
    }

    protected function _getMethod()
    {
        if ($this->params()->fromRoute('id', false)) {
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
        $response = $this->_getOptions();
        $response->getHeaders()
                ->addHeaderLine('Allow', implode(',', $this->_getOptions()));

        return $response;
    }

    /**
     * return json or xml
     */
    public function getList()
    {
//        $viewModel = $this->acceptableViewModelSelector($this->acceptCriteria);
//        if ($viewModel instanceof JsonModel) {
        return new JsonModel(array(
            'param' => $this->getListCountry(),
            0 => true
        ));
//        }
//        if ($viewModel instanceof XmlModel) {
//            $countries = array("countries" => array(
//                    'country' => $this->getListCountry()
//            ));
//            return new XmlModel($countries);
//        }
    }

    protected function getListCountry()
    {
        try {
            /** @var $countrys CountryTable   */
            $countrys = $this->getCountryTable()->fetchAll();
            $listCountry = [];
            foreach ($countrys as $country) {
                $listCountry[] = $country->toArray();
            }
            return $listCountry;
        } catch (\Exception $e) {
            $response = $this->getResponse();
            return ["error" => $e->getMessage()];
        }
    }

    public function get($id)
    {
        if (is_null($id) || $id === false) {
            return false;
        }
        $fields = $this->params()->fromQuery();
        if (is_numeric($id)) {
            $country = $this->getCountryTable()->getCountryByCode($id)->toArray();
        } else if (is_string($id)) {
            $country = $this->getCountryTable()->getCountryByAlpha($id)->toArray();
        }
        if (!empty($fields) && !empty($fields['fields'])) {
            $tmpTab = explode(',', $fields['fields']);
            foreach ($country as $key => $value) {
                if (false === array_search($key, $tmpTab)) {
                    unset($country[$key]);
                }
            }
        }
        return new JsonModel(array('data' => $country));
    }

    public function create($data)
    {
        try {
            $country = new Country();
            $country->exchangeArray($data);
            $result = $this->countryTable->saveCountry($country);
            $response = $this->getResponse();
            $response->setStatusCode(201);

            return new JsonModel(['created']);
        } catch (\Exception $e) {
            $response = $this->getResponse();
            $response->setStatusCode(400);
            return new JsonModel(['error' => $e->getMessage()]);
        }
    }

    public function update($id, $data)
    {
        $country = $this->getCountryTable()->getCountry($id);
        try {
            $country = $this->getCountryTable()->getCountry($id);
            $country->exchangeArray($data);
            $result = $this->countryTable->saveCountry($country);
            $response = $this->getResponse();
            $response->setStatusCode(201);

            return new JsonModel(['created']);
        } catch (\Exception $e) {
            $response = $this->getResponse();
            $response->setStatusCode(400);
            return new JsonModel(['error' => $e->getMessage()]);
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
        return $this->countryTable;
    }

}
