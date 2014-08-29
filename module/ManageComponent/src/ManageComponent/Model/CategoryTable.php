<?php
// module/Album/src/Album/Model/AlbumTable.php:
namespace ManageComponent\Model;


use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;



class CategoryTable extends AbstractTableGateway 
{
    protected $tableGateway;
    protected $table = 'tbl_category';
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Category());
        
        $this->initialize();
    }

    public function fetchAll(Select $select = null) {
        if (null === $select)
            $select = new Select();
        $select->from($this->table);
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }

    public function getTopic($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('topic_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveTopic(Topic $topic)
    {
        $data = array(
            'topic_name' => $topic->topic_name,
            'topic_description' =>  $topic->topic_description,
            'topic_status'=>1
        );

        $id = (int)$topic->topic_id;
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getTopic($id)) {
                $this->update($data, array('topic_id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
    public function deleteTopic($id)
    {
        $this->tableGateway->delete(array('topic_id' => $id));
    }
    public function getdepartmentCount(){

        $select = new Select();
        $select->from($this->table);
        $select->columns(array('departments_count' => new \Zend\Db\Sql\Expression('COUNT(cat_id)')));
        $select->where(array("{$this->table}.parent_id is NULL"));
        $resultSet = $this->selectWith($select);
        $rowset=$resultSet->current();        
        return $rowset;

        /*
        $id  = (int) 1;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
        */
        return false;
        /*
        $select = new Select();
        $select->from($this->table);
        $select->columns(array('departments_count' => new \Zend\Db\Sql\Expression('COUNT(cat_id)')));
        $select->where(array("{$this->table}.parent_id is NULL"));        
        //echo $select->getSqlString();
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();         
        return $resultSet;
        */
    }
    public function getdesignationCount(){
        $select = new Select();
        $select->from($this->table);
        $select->columns(array('designations_count' => new \Zend\Db\Sql\Expression('COUNT(cat_id)')));
        $select->where(array("{$this->table}.parent_id is NOT NULL"));
        $resultSet = $this->selectWith($select);
        $rowset=$resultSet->current();   
        return $rowset;
    }
}