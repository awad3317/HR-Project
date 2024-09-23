<?php
include('DB/database.php');
include('DB/user.php');

session_start();

$database = new Database();
$db = $database->connect();
$user= new user($db);

if(isset($_GET['logout'])){
    session_destroy();
    unset($_SESSION['user_id']);
}
if(isset($_SESSION['user_id'])){
    header("location: home.php");
}


if (isset($_POST['submit']))
{
    $username = $_POST['usrename'] ;
    $password = $_POST['password'] ;
    $user_id=$user->login($username,$password);
    if($user_id){
        session_start();
        $_SESSION['user_id']=$user_id;
        header("location: home.php");
    }else{
        $message="أسم المستخدم او كلمة السر غير صحيحة ";
    }

}




?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            direction: rtl;
            background: linear-gradient(to right, #ffffff 40%, #198754 40%);
            position: relative;
            overflow: hidden;
            
        }

        .container {
            position: relative;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 350px;
            text-align: center;
            z-index: 1;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header img {
            width: 50px;
        }

        .header a {
            text-decoration: none;
            color: black;
            font-size: 14px;
        }

        h2 {
            color: #198754;
            font-size: 24px;
            margin-bottom: 10px;
            margin-top:10px
        }

        p {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-bottom: 1px solid #ccc;
            font-size: 16px;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-bottom: 1px solid #1e2d58;
        }

        .forgot-password {
            text-align: left;
            font-size: 12px;
            color: #1e2d58;
            margin-bottom: 20px;
        }

        .forgot-password a {
            text-decoration: none;
            color: #1e2d58;
        }

        .login-btn {
            background-color: #198754;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .login-btn:hover {
            background-color: #198760;
        }

        .register {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }

        .register a {
            text-decoration: none;
            color: #1e2d58;
        }

        /* الخط المتعرج */
        .wave {
            position: absolute;

            top: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" d="M0,64L30,85.3C60,107,120,149,180,165.3C240,181,300,171,360,149.3C420,128,480,96,540,80C600,64,660,64,720,80C780,96,840,128,900,133.3C960,139,1020,117,1080,101.3C1140,85,1200,75,1260,85.3C1320,96,1380,128,1410,144L1440,160L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z"></path></svg>') no-repeat;
            background-size: cover;
            transform: translateX(-50%);
            z-index: 0;
            transform: rotate(90deg);
        }
    </style>
</head>
<body>
    

<div class="wave"></div>
<div class="container">
<div class="sidebar-brand-icon ">
            <img src="img/Logo.png" alt="logo" srcset="" width="60px" height="60px">
        </div>

    <h2 >مرحباً بعودتك!</h2>
    <p>الرجاء إدخال التفاصيل الخاصة بك</p>
    <?php if(isset($message)){ ?>
        <div style="text-align:center" class="alert alert-secondary"><?=$message?></div>
    <?php }?>
   <?php

   ?>
    <form method="post">
        <input type="text" placeholder="اسم المستخدم" name="usrename" required>
        <input type="password" placeholder="كلمة المرور" name="password" required>
        <div class="forgot-password">
        </div>
        <button type="submit" class="login-btn" name="submit" >تسجيل الدخول</button>
    </form>
</div>

</body>
</html>
