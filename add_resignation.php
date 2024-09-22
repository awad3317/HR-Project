<?php
include('DB/database.php');
include('DB/employee.php');
include('Validattion/Validator.php');
include('DB/resignation.php');
$database = new Database();
$db = $database->connect();
include("check_session.php");
if(isset($_POST['save'])){
    $data=[
        'reason'=>$_POST['reason'],
        'type'=>$_POST['type'],
        'date'=>date_format(date_create(),'Y-m-d'),
        'employee_id'=>$_POST['emp']
    ];
    $resignation= new resignation($db);
    $resignation->Create($data);
    header("location: resignation.php");
}
$employee= new employee($db);
$emps=$employee->All();


?>

<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>HR - Dashboard</title>

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
                            <li class="breadcrumb-item "><a href="Employee.php" class='text-success'>الإستقالات</a> </li>
                            <li class="breadcrumb-item active">طلب إستقاله  </li> 
                         </ul>
                    <h1 class="h3 mb-2 text-gray-800">طلب  إستقاله </h1>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">السبب: <span class="text-danger">*</span><span class="text-danger"></label>
                                    <input type="text" class="form-control" name="reason" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                        <div class="col-md-6">
                <div class="form-group">
                    <label>الموظف: <span class="text-danger">*</span></label>
                    <select class="form-control"  name="emp" required>
                        <option value="">أختر اسم الموظف</option>
                        <?php foreach($emps as $emp){?>
                            <option value="<?=$emp['id']?>"> <?=$emp['name']?></option>
                                <?php }?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label >النوع: <span class="text-danger">*</span></label>
                    <select class="form-control" name="type" required>
                        <option selected value="0"> إستقالة</option>
                        <option value="1"> إقاله</option>
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" name="save" class="btn btn-outline-success">تأكيد الطلب </button>
    </form>
                

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

    <script>
    // تحديث نص التسمية عند اختيار ملف
    document.querySelector('.custom-file-input').addEventListener('change', function (event) {
        const fileName = event.target.files[0]?.name || 'تصفح';
        const label = event.target.nextElementSibling;
        label.textContent = fileName;
    });
</script>

</body>


</html>