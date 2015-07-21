<?php

namespace Country\Model;

use Zend\Db\TableGateway\TableGateway;

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
        var_dump($country);
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
        if (strlen($alpha) > 2) {
            $rowset = $this->tableGateway->select(['alpha3' => $alpha]);
            $row = $rowset->count();
        } else {
            $rowset = $this->tableGateway->select(['alpha2' => $alpha]);
            $row = $rowset->count();
        }
        if (!$row) {
            throw new Exception("Ne trouve pas l'id $id");
        }
        return $row;
    }

}
