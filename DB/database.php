<?php 
class database{
    private $servername='localhost';
    private $username='root';
    private $password='';
    private $db='hr-project';
    private $connection;

    public function __construct(){
        $this->connection = null;
    }
    public function connect(){
       $this->connection= new mysqli($this->servername,$this->username,$this->password,$this->db);
       return $this->connection;
    }
   
    
    

}



?>