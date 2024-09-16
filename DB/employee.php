<?php 

class employee{
    private $connection;
    private $table = 'employees';
    public function __construct($db) {
        $this->connection = $db;
    }

    public function All(){
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result; 
    }

    public function select($query){
        $result=$this->connection->query($query);
        return $result; 
    }
}


?>