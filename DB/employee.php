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
        $stmt=$this->connection->prepare("INSERT INTO " . $this->table . "(name , basic_salary , sex , start_date , birthday , phone , address , imge , divinity_no , department_id , jop_id , email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
        $stmt->bind_param('ssisssssssss',$data['name'],$data['basic_salary'],$data['sex'],$data['start_date'],$data['birthdate'],$data['phone'],$data['address'],$data['image'],$data['divinity_no'],$data['department'],$data['jop'],$data['email']);
        $stmt->execute();
        return $this->connection->insert_id;
    }

    public function Update($data,$id){
        $name=$data['name'];
        $basic_salary=$data['basic_salary'];
        $sex=$data['sex'];
        $start_date=$data['start_date'];
        $birthdate=$data['birthdate'];
        $phone=$data['phone'];
        $address=$data['address'];
        $image=$data['image'];
        $divinity_no=$data['divinity_no'];
        $department=$data['department'];
        $jop=$data['jop'];
        $email=$data['email'];
        $query="UPDATE employees SET email='$email',name ='$name', basic_salary=$basic_salary,sex=$sex,start_date='$start_date',birthday='$birthdate',phone='$phone',address='$address',imge='$image',divinity_no='$divinity_no',department_id=$department,jop_id=$jop WHERE id = $id";
        $result=$this->connection->query($query);
    }
}


?>