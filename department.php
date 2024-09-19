<?php 
include('DB/database.php');
include('DB/department.php');
include('Validattion/Validator.php');
session_start();
unset($_SESSION['data_basic']);
unset($_SESSION['allowances']);
$database = new Database();
$db = $database->connect();
$department=new department($db);

if(isset($_GET['id'])){
$id= $_GET['id'];
$department->delete($id);
}
if(isset($_POST['save'])){
    $data=[
    'name'=>$_POST['name'],
    'description'=>$_POST['description'],
    ];
    $id=$department->Create($data);
}
$departments=$department->All();
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
                        <li class="breadcrumb-item active">الاقسام  </li> 
                    </ul>
                    <h1>إضافة قسم جديد</h1>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">القسم: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" >
                                </div>
                            </div>
                        
                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email"> التفاصيل : </label>
                                    <input type="text" class="form-control"  id="description" name="description">
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="save" class="btn btn-primary">إضافة القسم</button>
                    </form>
                    <h1 class="h3 mb-2 mt-5 text-gray-800">الاقسام</h1>
                    
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>القسم</th>
                                        <th>تفاصيل</th>
                                        <th>حذف</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($departments as $department){ 
                                    $count++ ?>
                                    <tr>
                                        <td><?=$count?></td>
                                        <td><?=$department['name']?></td>
                                        <td><?=$department['description']?></td>
                                        <td><a href="department.php?id=<?=$department['id']?>" class="btn btn-outline-danger" >حذف</a></td>
                                    </tr>
                                    <?php }?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
                <!-- /.container-fluid -->

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