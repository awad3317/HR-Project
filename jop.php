<?php 
include('DB/database.php');
include('DB/jop.php');
$database = new Database();
$db = $database->connect();
$jop=new jop($db);

if(isset($_GET['id'])){
$id= $_GET['id'];
$jop->delete($id);
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
                    <h1 class="h3 mb-2 text-gray-800">الوظائف</h1>
                    
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            
                        
                            <a class="btn btn-primary" href="add_jop.php">إضافة</a>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الوظيفة</th>
                                        <th>حذف</th>
                                        <th>تعديل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($jops as $jop){ 
                                    $count++ ?>
                                    <tr>
                                        <td><?=$count?></td>
                                        <td><?=$jop['name']?></td>
                                        <td><a href="jop.php?id=<?=$jop['id']?>" class="btn btn-primary" >حذف</a></td>
                                        <td><a href="home.php" class="btn btn-danger" name="Update" value="<?=$jop['id']?>">تعديل</a></td>
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
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php include("Logout_model.html") ?>

    <!-- Bootstrap core JavaScript-->
    <?php include("script.html") ?>

</body>

</html>