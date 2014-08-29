<?php
// module/Album/src/Album/Model/AlbumTable.php:
namespace TopicManagement\Model;


use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;


class TopicTable extends AbstractTableGateway 
{
    protected $tableGateway;
    protected $table = 'tbl_topic';
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Topic());
        
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
        //print_r($data); die;

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
}