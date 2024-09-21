<?php 

class leave_type{
    private $connection;
    private $table = 'leave_type';
    public function __construct($db) {
        $this->connection = $db;
    }
    public function All(){
        $query = "SELECT * FROM " . $this->table;
        $result=$this->connection->query($query);
        return $result; 
    }
    public function find($id){
        $query = "SELECT * FROM " . $this->table . " WHERE id = $id";
        $result=$this->connection->query($query);
        return $result; 
    }
    public function delete($id){
        $query = "DELETE FROM " . $this->table . " WHERE id = $id";
        $result=$this->connection->query($query);
        return $result; 
    }
    public function Create($data){
        $stmt=$this->connection->prepare("INSERT INTO " . $this->table . "(type) VALUES (?)");
        $stmt->bind_param('s',$data['type']);
        $stmt->execute();
        return $this->connection->insert_id;
    }
}

?>