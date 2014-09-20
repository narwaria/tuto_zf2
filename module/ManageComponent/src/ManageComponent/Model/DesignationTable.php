<?php
namespace ManageComponent\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class DesignationTable extends AbstractTableGateway {
	
	protected $tableGateway;
    protected $table	= 'tbl_designation';
    protected $tbl_dept	= 'tbl_department';
    protected $adapter;
    
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function fetchAll($query=null, $paginated=false) {

        $select = new Select();
        //$select->from($this->table, array('desig_id', 'desig_name', 'dept_id', 'client_id'));
        $select->from(array('desig' => $this->table), array('desig_id', 'desig_name', 'dept_id', 'client_id'));
        
        if($query['desig_name']){ 
            $select->where->like('desig.desig_name', '%'.$query['desig_name'].'%');
        }
        
        if($query['dept_id']){
            $select->where->equalTo('desig.dept_id', $query['dept_id']);
        }
        
        //$select->join($this->tbl_dept, 'tbl_department.dept_id = tbl_designation.dept_id', array('dept_name'), 'left');
		$select->join(array('dept' => $this->tbl_dept), 'dept.dept_id = desig.dept_id', array('dept_name'=>'dept_name'), 'left');
		
		$select->order('desig.desig_name');
		
        if($paginated) {
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Designation());
            
            $paginatorAdapter	= new DbSelect($select, $this->adapter, $resultSetPrototype);
            $paginator			= new Paginator($paginatorAdapter);
            
            return $paginator;
        }
        
        $resultSet = $this->selectWith($select);
        
        return $resultSet;
    }

    public function saveDesig($desig) { 
        
        $data = array(
            'desig_name'        => $desig->desig_name,
            'dept_id'			=> $desig->dept_id,
            'client_id'         => $desig->client_id,
            'created_by_user_id'=> $desig->created_by_user_id,
            'created_date'      => $desig->created_date,
        );
        
        $id = (int)$desig->desig_id;
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getDesig($id)) {
                $this->update($data, array('desig_id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
    
    public function updateDesig($desig) { 
        
        $data = array(
            'desig_name'=> $desig->desig_name,
            'dept_id'	=> $desig->dept_id,
        );
        
        $id = (int)$desig->desig_id;
        
        if ($this->getDesig($id)) {
            $this->update($data, array('desig_id' => $id));
        } else {
            throw new \Exception('Form id does not exist');
        }
    }
    
    public function getDesig($id=null, $desig=null, $dept=null) {
        $id  = (int) $id;
        
        if($id){
			$rowset = $this->select(array('desig_id' => $id));
		} else if(isset($design) && isset($dept)){
			$rowset = $this->select(array('desig_id' => $id));
		}
		
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function getDesigCount(){
        $select = new Select();
        $select->from($this->table);
        $select->columns(array('desig_count' => new \Zend\Db\Sql\Expression('COUNT(desig_id)')));
        //$select->where(array("{$this->table}.parent_id is NOT NULL"));
        $resultSet = $this->selectWith($select);
        $rowset=$resultSet->current();   
        return $rowset["desig_count"];
    }
    
    public function getDesigList(){ 
        $desigValues =   $this->fetchAll(); // get the skill names
        
        $desigArray		=   array();
        $desigArray['0']= "Please select";
        
        foreach($desigValues as  $desig){
            $desigArray[$desig['desig_id']]     =   $desig['desig_name'];        
        }
        return $desigArray;
    }
    
}
