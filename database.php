<?php 

class database{
    protected $servername='localhost';
    protected $username='root';
    protected $password='';
    protected $db='hr-project';
    private $conn;

    public function __construct(){
       $this->conection();
    }
    private function conection(){
       $this->conn= new mysqli($this->servername,$this->username,$this->password,$this->db);
    }
    public function delete($table,$id){
        $result=$this->conn->query("DELETE FROM $table WHERE id =$id");

    }
    public function All($table){
        $result=$this->conn->query("SELECT * FROM $table");
        return $result;
    }
    public function select($query){
        $result=$this->conn->query($query);
        return $result;
    }

}



?>