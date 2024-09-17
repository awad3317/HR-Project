<?php 

class jop{
    private $connection;
    private $table = 'jops';
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
        $stmt=$this->connection->prepare("INSERT INTO " . $this->table . "(name) VALUES (?)");
        $stmt->bind_param('s',$data['name']);
        $stmt->execute();
        return $this->connection->insert_id;
    }
}

?>