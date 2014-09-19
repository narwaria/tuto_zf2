<?php
namespace ManageComponent\Model;


use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;

class SkillTable extends AbstractTableGateway 
{
	protected $tableGateway;
    protected $table = 'tbl_skill';
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
    
    public function getSkillCount(){
		$select = new Select();
		$select->from($this->table);
		$select->columns(array('skills_count' => new \Zend\Db\Sql\Expression('COUNT(skill_id)')));
		//$select->where(array("skill_status"=>1));
		$resultSet = $this->selectWith($select);
		$rowset=$resultSet->current();             
		return $rowset["skills_count"];
    }
}
