<?php 
include('DB/database.php');
include('DB/department.php');
include('Validattion/Validator.php');
session_start();
unset($_SESSION['data_basic']);
unset($_SESSION['allowances']);
$database = new Database();
$db = $database->connect();
if(isset($_POST['save'])){
    $data=[
    'name'=>$_POST['name'],
    'description'=>$_POST['description'],
    ];
    $rules=[
        'name'=>'required|min:2'
    ];
    $validation= new Validator($db);
    if($validation->validate($data,$rules)){
        $department=new department($db);
        $id=$department->Create($data);
        if($id){
            header("location: department.php");
        }

    } 
   else{
    $validation=$validation->errors(); 
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Tables</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/bootstrap.css">
   
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
       <?php include('Sidebar.html') ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
               <?php include('navbar.html') ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <ul  class="breadcrumb m-3">
                            <li class="breadcrumb-item"> <a href="home.php">الرئيسية</a></li> 
                            <li class="breadcrumb-item "><a href="department.php">الاقسام</a> </li>
                            <li class="breadcrumb-item active">إضافة قسم جديد </li> 
                    </ul>
                    <h1>إضافة قسم جديد</h1>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">القسم: <span class="text-danger">*</span><span class="text-danger"><?php if(isset($validation['name'][0])){echo $validation['name'][0];}?></span></label>
                                    <input type="text" class="form-control" id="name" name="name" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email"> التفاصيل : </label>
                                    <input type="text" class="form-control" value="<?=$data['description']??''?>"  id="description" name="description" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="save" class="btn btn-primary">إضافة القسم</button>
                    </form>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include("footer.html") ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
   <?php include("Logout_model.html") ?>

    <!-- Bootstrap core JavaScript-->
   <?php include("script.html") ?>

</body>

</html>