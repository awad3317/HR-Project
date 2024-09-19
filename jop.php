<?php 
include('DB/database.php');
include('DB/jop.php');
include('Validattion/Validator.php');
session_start();
unset($_SESSION['data_basic']);
unset($_SESSION['allowances']);
$database = new Database();
$db = $database->connect();
$jop=new jop($db);

if(isset($_GET['id'])){
$id= $_GET['id'];
$jop->delete($id);
}
if(isset($_POST['save'])){
    $data=[
    'name'=>$_POST['name'],
    ];
    $rules=[
        'name'=>'required'
    ];
    $validation= new Validator($db);
    if($validation->validate($data,$rules)){
        $id=$jop->Create($data);
    } 
   else{
    $validation=$validation->errors(); 
   }
}
$jops=$jop->All();
$count=0;
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

                    <ul  class="breadcrumb m-3">
                        <li class="breadcrumb-item"> <a href="home.php">الرئيسية</a></li> 
                        <li class="breadcrumb-item active">الوظائف  </li> 
                    </ul>
                    <h3 class="text-gray-800">إضافة وظيفة جديده</h3>
                    <form action="" method="POST" enctype="multipart/form-daaa">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">الوظيفة: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="save" class="btn btn-primary">إضافة الوظيفة</button>
                    </form>
                    <h3 class="mb-2 mt-5 text-gray-800">الوظائف</h3>
                    
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>الوظيفة</th>
                                            <th>حذف</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($jops as $jop){ 
                                        $count++ ?>
                                        <tr>
                                            <td><?=$count?></td>
                                            <td><?=$jop['name']?></td>
                                            <td><a href="jop.php?id=<?=$jop['id']?>" class="btn btn-outline-danger" >حذف</a></td>
                                        </tr>
                                        <?php }?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->

        </div>
    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <?php include("footer.html") ?>
    <!-- End of Footer -->

    <!-- Scroll to Top Button-->
    <?php include("Scroll.html") ?>

    <!-- Logout Modal-->
    <?php include("Logout_model.html") ?>

    <!-- Bootstrap core JavaScript-->
    <?php include("script.html") ?>

</body>

</html>