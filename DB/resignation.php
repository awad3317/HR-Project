<?php 

class resignation{
    private $connection;
    private $table = 'resignations';
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

    public function select($query){
        $result=$this->connection->query($query);
        return $result; 
    }

    public function delete($id){
        $query = "DELETE FROM " . $this->table . " WHERE id = $id";
        $result=$this->connection->query($query);
        return $result; 
    }
    
    public function Create($data){
        $stmt=$this->connection->prepare("INSERT INTO " . $this->table . "(reason, type, date, employee_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sisi',$data['reason'],$data['type'],$data['date'],$data['employee_id']);
        $stmt->execute();
        return $this->connection->insert_id;
    }
}

?>