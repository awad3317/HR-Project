<?php 
class employee_file{
    private $connection;
    private $table = 'employee_file';
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
        $stmt=$this->connection->prepare("INSERT INTO " . $this->table . "(employee_id, file_id, path) VALUES (?,?,?)");
        $stmt->bind_param('iis',$data['type1'],$data['employee_id'],$data['allowance1']);
        $stmt->execute();
        $stmt->bind_param('iis',$data['type2'],$data['employee_id'],$data['allowance2']);
        $stmt->execute();
        $stmt->bind_param('iis',$data['type2'],$data['employee_id'],$data['allowance2']);
        $stmt->execute();
        return $this->connection->insert_id;
    }
    public function Create($data){
        $stmt=$this->connection->prepare("INSERT INTO " . $this->table . "(employee_id, file_id, path) VALUES (?,?,?)");
        $stmt->bind_param('iis',$data['type1'],$data['employee_id'],$data['allowance1']);
        $stmt->execute();
        return $this->connection->insert_id;
    }
}
?>