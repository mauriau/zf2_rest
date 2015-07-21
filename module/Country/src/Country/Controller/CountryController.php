<?php
namespace Country\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Country\Model\Country;
use Country\Form\CountryForm;

class CountryController extends AbstractActionController
{
    protected $countryTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'countrys' => $this->getCountryTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new CountryForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $country = new Country();
            $form->setInputFilter($country->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country->exchangeArray($form->getData());
                $this->getCountryTable()->saveCountry($country);

                // Redirect to list of countrys
                return $this->redirect()->toRoute('country');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('country', array(
                'action' => 'add'
            ));
        }
        $country = $this->getCountryTable()->getCountry($id);

        $form  = new CountryForm();
        $form->bind($country);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($country->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCountryTable()->saveCountry($form->getData());

                // Redirect to list of countrys
                return $this->redirect()->toRoute('country');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('country');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getCountryTable()->deleteCountry($id);
            }

            // Redirect to list of countrys
            return $this->redirect()->toRoute('country');
        }

        return array(
            'id'    => $id,
            'country' => $this->getCountryTable()->getCountry($id)
        );
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