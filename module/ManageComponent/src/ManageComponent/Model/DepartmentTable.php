<?php
namespace ManageComponent\Model;


use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;

class DepartmentTable extends AbstractTableGateway 
{
	protected $tableGateway;
    protected $table = 'tbl_department';
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
    
    public function getDepartmentCount(){

        $select = new Select();
        $select->from($this->table);
        $select->columns(array('departments_count' => new \Zend\Db\Sql\Expression('COUNT(dept_id)')));
        //$select->where(array("{$this->table}.parent_id is NULL"));
        $resultSet = $this->selectWith($select);
        $rowset=$resultSet->current();        
        return $rowset["departments_count"];
    }
}
