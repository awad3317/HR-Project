<?php 

class file_type{
    private $connection;
    private $table = 'file_type';
    public function __construct($db) {
        $this->connection = $db;
    }

    public function All(){
        $query = "SELECT * FROM " . $this->table;
        $result=$this->connection->query($query);
        return $result; 
    }
}

?>