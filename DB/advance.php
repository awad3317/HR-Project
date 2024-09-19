<?php 
class advance{
    private $connection;
    private $table = 'advances';
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
    public function Create($data){
        $stmt=$this->connection->prepare("INSERT INTO " . $this->table . "(amount, date, employee_id) VALUES (?, ?, ?)");
        $stmt->bind_param('isi',$data['amount'],$data['date'],$data['employee_id']);
        $stmt->execute();
        return $this->connection->insert_id;
    }

}
?>