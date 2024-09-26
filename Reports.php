<?php 
include('DB/database.php');
include('DB/employee.php');

unset($_SESSION['data_basic']);
unset($_SESSION['allowances']);
$database = new Database();
$db = $database->connect();
include("check_session.php");
$employee = new employee($db);
$employees=$employee->All();
$count=0;
?>
<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> عرض الموظفين</title>

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
                        <li class="breadcrumb-item active">التقارير </li> 
                    </ul>
                    <h1 class="h3 mb-2 text-gray-800">الموظفين</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                       
                        <div class="card-body">
                            <div class="table-responsive">
                               
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="bg-gradient-success text-gray-100">#</th>
                                            <th class="bg-gradient-success text-gray-100">الموظف</th>
                                            <th class="bg-gradient-success text-gray-100">شهادة خبرة</th>
                                            <th class="bg-gradient-success text-gray-100">بيانات الموظف</th>
                                        </tr>
                                    </thead>
                                    
                                   <tbody>
                                   <?php foreach($employees as $employee){
                                   $count++;
                                    ?>
                                    <tr>
                                        <td><?=$count?></td>
                                        <td><?=htmlspecialchars($employee['name'])?></td>
                                        <td><a class="btn btn-outline-secondary" href="Certificate_of_Experience.php?id=<?=$employee['id']?>">توليد</a></td>
                                        <td><a class="btn btn-outline-secondary" href="employee_data.php?id=<?=$employee['id']?>">توليد</a></td>
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

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <?php include("Scroll.html") ?>

    <!-- Logout Modal-->
    <?php include('Logout_model.html') ?>

    <!-- Bootstrap core JavaScript-->
   <?php include("script.html") ?>

</body>

</html>