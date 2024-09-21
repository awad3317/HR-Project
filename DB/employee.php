<?php 

class employee{
    private $connection;
    private $table = 'employees';
    public function __construct($db) {
        $this->connection = $db;
    }

    public function All(){
        $query = "SELECT * FROM " . $this->table;
        $result=$this->connection->query($query);
        return $result; 
    }

    public function Find($id){
        $query = "SELECT * FROM " . $this->table . " WHERE id = $id";
        $result=$this->connection->query($query);
        return $result; 
    }

    public function select($query){
        $result=$this->connection->query($query);
        return $result; 
    }
    
    public function Count(){
        $query = "SELECT COUNT(*) AS 'count' FROM " . $this->table;
        $result=$this->connection->query($query);
        return $result; 
    }
    public function Create($data){
        $stmt=$this->connection->prepare("INSERT INTO " . $this->table . "(name , basic_salary , sex , start_date , birthday , phone , address , imge , divinity_no , department_id , jop_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssissssssss',$data['name'],$data['basic_salary'],$data['sex'],$data['start_date'],$data['birthdate'],$data['phone'],$data['address'],$data['image'],$data['divinity_no'],$data['department'],$data['jop']);
        $stmt->execute();
        return $this->connection->insert_id;
    }
}


?>