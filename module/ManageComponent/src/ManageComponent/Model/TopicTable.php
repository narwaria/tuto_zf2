<?php
// module/Album/src/Album/Model/AlbumTable.php:
namespace ManageComponent\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert,Zend\Db\Sql\Delete;
use Zend\Db\Sql\Sql;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;


class TopicTable extends AbstractTableGateway 
{
    protected $tableGateway;
    protected $table        = 'tbl_topic'; 
    protected $topicSkill   = 'tbl_topic_skill';
    protected $adapter;


    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        //$this->resultSetPrototype = new ResultSet();
        //$this->resultSetPrototype->setArrayObjectPrototype(new Topic());        
       // $this->initialize();
    }

    public function fetchAll($query=null, $paginated=false) {
        //if (null === $select) $select = new Select();
        
        $select = new Select();
        $select->from($this->table);
        
        if(isset($query)){
            $select->where->like('topic_name', '%'.$query.'%');
        }
        
        $select->order('topic_name');
        
        if($paginated) {
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Topic());
            $paginatorAdapter = new DbSelect($select, $this->adapter, $resultSetPrototype);
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }
        
        $resultSet = $this->selectWith($select);
        //$resultSet->buffer();
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

    public function saveTopic($topic) { 
        
        $data = array(
            'topic_name'        => $topic->topic_name,
            'client_id'         => $topic->client_id,
            'created_by_user_id'=> $topic->created_by_user_id,
            'created_date'      => $topic->created_date,
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
    
    public function updateTopic($topic) { 
        
        $data = array(
            'topic_name' => $topic->topic_name,
        );
        
        $id = (int)$topic->topic_id;
        
        if ($this->getTopic($id)) {
            $this->update($data, array('topic_id' => $id));
        } else {
            throw new \Exception('Form id does not exist');
        }
    }

    public function deleteTopic($id){
        $this->tableGateway->delete(array('topic_id' => $id));
    }
    
    public function getTopicCount(){
        $select = new Select();
        $select->from($this->table);
        $select->columns(array('topics_count' => new \Zend\Db\Sql\Expression('COUNT(topic_id)')));
        //$select->where(array("topic_status"=>1));
        $resultSet = $this->selectWith($select);
        $rowset=$resultSet->current();             
        return $rowset["topics_count"];
    }

    public function saveSkillByTopic($topic_id=null, $skill_id=null){            
        $sql        = new Sql($this->adapter);
        $insert     = new Insert($this->topicSkill);
        
        $insert->values(array('topic_id'=>(int)$topic_id,'skill_id'=>(int)$skill_id));             
        $selectString = $sql->getSqlStringForSqlObject($insert);
        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
    }
    
    public function getSkillByTopic($topic_id=null, $tech_id=null){ 
        $sql    = new Sql($this->adapter);
        $select = new Select($this->topicSkill);
        
        if($topic_id!=NULL) {
            $select->where(array("topic_id"=>$topic_id));
        }

        if($tech_id!=NULL){
            $select->where(array("tech_id"=>$tech_id));
        }
        
        $selectString = $sql->getSqlStringForSqlObject($select); 
        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);        
        return $results;
    }
    
    public function deleteSkillByTopic($topic_id=NULL){
        $sql        =   new Sql($this->adapter);
        $delete     =   new Delete($this->topicSkill);
        $delete->where(array("topic_id"=>$topic_id));
        $selectString = $sql->getSqlStringForSqlObject($delete);
        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
    }

    public function getlistdata(){
        return array("1"=>"Helo","2"=>"hi");
    }
}
