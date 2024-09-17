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
    public function Count(){
        $query = "SELECT COUNT(*) AS 'count' FROM " . $this->table;
        $result=$this->connection->query($query);
        return $result; 
    }
}

?>