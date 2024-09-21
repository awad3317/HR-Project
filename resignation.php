<?php
include('DB/database.php');
include('Validattion/Validator.php');
include('DB/resignation.php');

$database = new Database();
$db = $database->connect();
$resignation= new resignation($db);
$resignations=$resignation->select("SELECT employees.id AS emp_id, employees.name AS emp_name,resignations.*  FROM employees JOIN resignations ON employees.id = resignations.employee_id");
?>



<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نموذج استقالة الموظف</title>
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
               <?php include('navbar.php') ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <ul  class="breadcrumb m-3">
                        <li class="breadcrumb-item"> <a href="home.php" class='text-success'>الرئيسية</a></li> 
                        <li class="breadcrumb-item active">الإستقالات </li> 
                    </ul>
                    <a href="add_resignation.php"><button id="add-leave-btn" class="btn btn-outline-success">طلب استقالة</button></a>
                    <h1 class="h3 mb-2 mt-3 text-gray-800">الإستقالات</h1>
                    
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                    <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th class="bg-gradient-success text-gray-100">#</th>
                                <th class="bg-gradient-success text-gray-100">الموظف</th>
                                <th class="bg-gradient-success text-gray-100">النوع</th>
                                <th class="bg-gradient-success text-gray-100">التاريخ</th>
                                <th class="bg-gradient-success text-gray-100">السبب</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $count=0; foreach($resignations as $res){ $count++?>
                                    <tr>
                                        <td><?=$count?></td>
                                        <td><?=$res['emp_name']?></td>
                                        <td><?php if($res['type'] == '0'){echo 'استقالة';}else{echo'إقاله';} ?></td>
                                        <td><?=$res['date']?></td>
                                        <td><?=$res['reason']?></td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
                <!-- /.container-fluid -->

            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include("footer.html") ?>
            <!-- End of Footer -->

        
        <!-- End of Content Wrapper -->

   
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <?php include("Scroll.html")?>
    <!-- Logout Modal-->
    <?php include("Logout_model.html") ?>

    <!-- Bootstrap core JavaScript-->
    <?php include("script.html") ?>
</body>
</html>
