<?php 

class leave{
    private $connection;
    private $table = 'leaves';
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
        $stmt=$this->connection->prepare("INSERT INTO " . $this->table . "(start, end, 	leave_type_id, employee_id ) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssii',$data['start'],$data['end'],$data['leave_type_id'],$data['employee_id']);
        $stmt->execute();
        return $this->connection->insert_id;
    }
}

?>