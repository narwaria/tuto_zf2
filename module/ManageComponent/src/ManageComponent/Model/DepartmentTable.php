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

class DepartmentTable extends AbstractTableGateway {
	
	protected $tableGateway;
    protected $table = 'tbl_department';
    protected $adapter;
    
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function fetchAll($query=null, $paginated=false) {
        $select = new Select();
        $select->from($this->table);
        
        if(isset($query)){
            $select->where->like('dept_name', '%'.$query.'%');
        }
        
        $select->order('dept_name');
        
        if($paginated) {
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Department());
            
            $paginatorAdapter	= new DbSelect($select, $this->adapter, $resultSetPrototype);
            $paginator			= new Paginator($paginatorAdapter);
            return $paginator;
        }
        
        $resultSet = $this->selectWith($select);
        return $resultSet;
    }
    
    public function getDepartment($id) {
        $id  = (int) $id;
        $rowset = $this->select(array('dept_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveDepartment($dept) { 
        
        $data = array(
            'dept_name'			=> $dept->dept_name,
            'client_id'         => $dept->client_id,
            'created_by_user_id'=> $dept->created_by_user_id,
            'created_date'      => $dept->created_date,
        );
        
        $id = (int)$dept->dept_id;
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getDepartment($id)) {
                $this->update($data, array('dept_id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
    
    public function updateDepartment($dept) { 
        
        $data = array(
            'dept_name' => $dept->dept_name,
        );
        
        $id = (int)$dept->dept_id;
        
        if ($this->getDepartment($id)) {
            $this->update($data, array('dept_id' => $id));
        } else {
            throw new \Exception('Form id does not exist');
        }
    }
    
    public function getDeptCount(){

        $select = new Select();
        $select->from($this->table);
        $select->columns(array('departments_count' => new \Zend\Db\Sql\Expression('COUNT(dept_id)')));
        //$select->where(array("{$this->table}.parent_id is NULL"));
        $resultSet = $this->selectWith($select);
        $rowset=$resultSet->current();        
        return $rowset["departments_count"];
    }
    
    public function getDeptList(){ 
        $deptValues =   $this->fetchAll(); // get the department names
        
        $deptArray    =   array();
        $deptArray['0'] = "Please select";
        
        foreach($deptValues as  $dept){
            $deptArray[$dept['dept_id']]     =   $dept['dept_name'];        
        }
        return $deptArray;
    }
    
}
