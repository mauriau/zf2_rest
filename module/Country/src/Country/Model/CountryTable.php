<?php

namespace Country\Model;

use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Country\Model\Country;

class CountryTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $sql = $this->tableGateway->getSql();
        $select = $sql->select();
        $select->limit(30);

        return $this->tableGateway->selectWith($select);
    }

    public function getCountry($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveCountry(Country $country)
    {
        $data = array(
            'code' => $country->code,
            'alpha2' => $country->alpha2,
            'alpha3' => $country->alpha3,
            'nomEnGb' => $country->nomEnGb,
            'nomFrFr' => $country->nomFrFr,
            'devise' => $country->devise,
            'tauxTva' => $country->tauxTva,
        );
        $id = (int) $country->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCountry($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteCountry($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function getCountryByAlpha($alpha)
    {
        $where = new Where();

        if (strlen($alpha) > 2) {
            $where->equalTo('alpha3', $alpha);
            $rowset = $this->tableGateway->select();
            $row = $rowset->current();
        } else {
            $where->equalTo('alpha2', $alpha);
            $rowset = $this->tableGateway->select();
            $row = $rowset->current();
        }
        if (!$row) {
            throw new Exception("Ne trouve pas l'id $alpha");
        }
        return $row;
    }

    public function getCountryByCode($code)
    {
        $where = new Where();
        $where->equalTo('code', $code);
        $rowset = $this->tableGateway->select($where);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $code");
        }
        return $row;
    }

}
