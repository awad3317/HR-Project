<?php 
include('DB/database.php');
include('DB/employee.php');
include('DB/allowance.php');
include('DB/file.php');
include('DB/jop.php');
include('DB/department.php');
include('DB/employee_file.php');
include('DB/allowance_employee.php');
include('Validattion/Validator.php');

session_start();

$database = new Database();
$db = $database->connect();

if(!isset($_GET['id'])){
    header("location: Employee.php");
}
$emp_id=$_GET['id'];

//Get the employee
$employee= new employee($db);
$emp = $employee->select("SELECT emp.*, dep.name AS dep_name, jop.name AS jop_name, sum(adv.amount) AS total
                        FROM employees AS emp 
                        JOIN departments AS dep ON emp.department_id = dep.id
                        JOIN jops AS jop ON emp.jop_id = jop.id
                        JOIN advances AS adv ON emp.id = adv.employee_id
                        WHERE emp.id = $emp_id");

$emp_allowance=$employee->select("SELECT allowance_employee.amount AS amount, allowances.name AS allowance_name FROM allowance_employee 
                                JOIN allowances ON allowance_employee.allowance_id = allowances.id 
                                WHERE allowance_employee.employee_id=$emp_id");
$emp_file=$employee->select("SELECT path,type FROM employee_file AS emp_file JOIN file ON emp_file.file_id = file.id WHERE emp_file.employee_id = $emp_id ")
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
                        <li class="breadcrumb-item "><a href="Employee.php" class='text-success'>الموظفين</a> </li>
                        <li class="breadcrumb-item active">تفاصيل الموظف  </li> 
                    </ul>
                    <h3 class="text-center">بيانات الموظف الاساسية</h3>
                    <div class="row mb-4 justify-content-center">
                        <?php foreach($emp as $e){ ?>
                        <div class="col-md-3 text-center">
                            <img id="profileImage" src="<?=$e['imge']?>" alt="صورة الموظف" class="img-thumbnail" width="200" height="200">
                        </div>
                            <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 custom-col">  
                                    <p><strong>الاسم:</strong> <?=$e['name']?> </p>     
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>رقم الهوية:</strong> <?=$e['divinity_no']?></p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>تاريخ الميلاد:</strong> <?=$e['birthday']?> </p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <?php
                                    $startDate = new DateTime($e['start_date']);
                                    $today = new DateTime();
                                    $interval = $startDate->diff($today);
                                    $years = $interval->y;
                                    $months = $interval->m;
                                    $days = $interval->d;
                                    ?>
                                    <p><strong>فترة العمل:</strong> <?= $years ?> سنوات و <?= $months ?> اشهر و <?= $days ?> ايام</p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>رقم التواصل:</strong> <?=$e['phone']?> </p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>العنوان:</strong> <?=$e['address']?></p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>الراتب الأساسي:</strong> <?=$e['basic_salary']?> </p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>الجنس:</strong> <?php echo $e['sex'] == true ? 'ذكر' : 'أنثى'; ?> </p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>القسم:</strong> <?=$e['dep_name']?> </p>
                                </div>
                                <div class="col-md-6 custom-col">
                                    <p><strong>الوظيفة:</strong> <?=$e['jop_name']?> </p>
                                </div>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                    
                    <div class="row mb-4 justify-content-center">
    <div class="col-md-6">
    <div class="d-flex justify-content-between align-items-center mb-1">
            <h3 class="text-center">بدلات الموظف</h3>
            <button class="btn btn-outline-secondary btn-sm">إضافة بدل</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered border-bottom-success" width="100%">
                
                <tbody>
                        <?php foreach($emp_allowance as $allowance) { ?>
                        <tr>
                            <th class="text-center bg-gray-200"><?=$allowance['allowance_name']?></th>
                            <th class="text-center"><?=$allowance['amount']?></th>
                        </tr>
                        <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center mb-1">
            <h3 class="text-center">مرفقات الموظف</h3>
            <button class="btn btn-outline-secondary btn-sm">إضافة مرفق</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered border-bottom-success" width="100%">
                
                <tbody>
                    
                    <?php foreach($emp_file as $file) { ?>
                        <tr>
                            <th class="text-center bg-gray-200"><?=$file['type']?></th>
                            <th class="text-center">
                                <a href="<?=$file['path']?>" target="_blank" class="btn btn-outline-secondary" download="مرفق">تحميل</a>
                                <a href="<?=$file['path']?>" target="_blank" class="btn btn-outline-success">فتح</a>
                            </th> 
                        </tr>
                    <?php } ?>
                    
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