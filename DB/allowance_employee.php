<?php 
class allowance_employee{
    private $connection;
    private $table = 'allowance_employee';
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
    public function CreateAll($data){
        $stmt=$this->connection->prepare("INSERT INTO " . $this->table . "(allowance_id , employee_id , amount) VALUES (?,?,?)");
        $stmt->bind_param('iii',$data['type1'],$data['employee_id'],$data['allowance1']);
        $stmt->execute();
        $stmt->bind_param('iii',$data['type2'],$data['employee_id'],$data['allowance2']);
        $stmt->execute();
        return $this->connection->insert_id;
    }
    public function Create($data){
        $stmt=$this->connection->prepare("INSERT INTO " . $this->table . "(allowance_id , employee_id , amount) VALUES (?,?,?)");
        $stmt->bind_param('iii',$data['type1'],$data['employee_id'],$data['allowance1']);
        $stmt->execute();
        return $this->connection->insert_id;
    }
}
?>