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
    public function Find($id){
        $query = "SELECT * FROM " . $this->table . " WHERE id = $id";
        $result=$this->connection->query($query);
        return $result; 
    }
    public function delete($id){
        $query = "DELETE * FROM " . $this->table . " WHERE id= $id";
    }
    public function select($query){
        $result=$this->connection->query($query);
        return $result; 
    }

    public function login($username, $password) {
        $result=$this->select("SELECT * FROM ". $this->table ." WHERE username = '$username' AND password = '$password' ");
       if($result->num_rows == 0){
        return false;
       } 
       else{
        foreach($result as $res){
           return $res['id'];
        }
       }
    }
}
?>