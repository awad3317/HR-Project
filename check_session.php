<?php 
include('DB/user.php');

session_start();
$user= new user($db);
if(!isset($_SESSION['user_id'])){
    header("location: login.php");
}
$users=$user->Find($_SESSION['user_id']);

?>