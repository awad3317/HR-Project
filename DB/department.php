<?php 


class department{
    private $connection;
    private $table = 'departments';
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
    public function Count(){
        $query = "SELECT COUNT(*) AS 'count' FROM " . $this->table;
        $result=$this->connection->query($query);
        return $result; 
    }
    public function delete($id){
        $query = "DELETE FROM " . $this->table . " WHERE id = $id";
        $result=$this->connection->query($query);
        return $result; 
    }
    public function Create($data){
        $stmt=$this->connection->prepare("INSERT INTO " . $this->table . "(name , description) VALUES (?, ?)");
        $stmt->bind_param('ss',$data['name'],$data['description']);
        $stmt->execute();
        return $this->connection->insert_id;
    }
}

?>