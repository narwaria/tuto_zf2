<?php
namespace ManageComponent\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\ResultSet\ResultSet;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class SkillTable extends AbstractTableGateway {
	
	protected $tableGateway;
    protected $table = 'tbl_skill';
    protected $adapter;
    
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function fetchAll($query=null, $paginated=false) {
        $select = new Select();
        $select->from($this->table);
        
        if(isset($query)){
            $select->where->like('skill_name', '%'.$query.'%');
        }
        
        $select->order('skill_name');
        
        if($paginated) {
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Skill());
            $paginatorAdapter = new DbSelect($select, $this->adapter, $resultSetPrototype);
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }
        
        $resultSet = $this->selectWith($select);
        return $resultSet;
    }
    
    public function getSkill($id) {
        $id  = (int) $id;
        $rowset = $this->select(array('skill_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveSkill($skill) { 
        
        $data = array(
            'skill_name'        => $skill->skill_name,
            'client_id'         => $skill->client_id,
            'created_by_user_id'=> $skill->created_by_user_id,
            'created_date'      => $skill->created_date,
        );
        
        $id = (int)$skill->skill_id;
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getskill($id)) {
                $this->update($data, array('skill_id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
    
    public function updateSkill($skill) { 
        
        $data = array(
            'skill_name' => $skill->skill_name,
        );
        
        $id = (int)$skill->skill_id;
        
        if ($this->getskill($id)) {
            $this->update($data, array('skill_id' => $id));
        } else {
            throw new \Exception('Form id does not exist');
        }
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
    
    public function getSkillList(){ 
        $skillsValues =   $this->fetchAll(); // get the skill names
        
        $skillArray		=   array();
        $skillArray['0']= "Please select";
        
        foreach($skillsValues as  $skill){
            $skillArray[$skill["skill_id"]]     =   $skill["skill_name"];        
        }
        return $skillArray;
    }
    
}
