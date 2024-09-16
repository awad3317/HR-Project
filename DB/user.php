<?php 

class user{
    private $connection;
    private $table = 'users';
    public function __construct($db) {
        $this->connection = $db;
    }
    public function All() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result; 
    }
}
?>