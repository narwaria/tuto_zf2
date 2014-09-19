<?php
namespace ManageComponent\Model;


use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;

class DesignationTable extends AbstractTableGateway 
{
	protected $tableGateway;
    protected $table = 'tbl_designation';
    protected $adapter;
    
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function fetchAll(Select $select = null) { 
        if (null === $select)
            $select = new Select();
        $select->from($this->table);
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }
    
    public function getDesignationCount(){
        $select = new Select();
        $select->from($this->table);
        $select->columns(array('designations_count' => new \Zend\Db\Sql\Expression('COUNT(desig_id)')));
        //$select->where(array("{$this->table}.parent_id is NOT NULL"));
        $resultSet = $this->selectWith($select);
        $rowset=$resultSet->current();   
        return $rowset["designations_count"];
    }
    
}
