<?php
require 'vendor/ar-php/ar-php/I18N/Arabic/Glyphs.php';
require 'vendor/autoload.php';
include('DB/database.php');
include('DB/employee.php');
$database = new Database();
$db = $database->connect();
include("check_session.php");
$employee=new employee($db);
if(isset($_GET['id'])){
    $emp_id=$_GET['id'];
}
$emp = $employee->select("SELECT emp.*, dep.name AS dep_name, jop.name AS jop_name
                        FROM employees AS emp 
                        JOIN departments AS dep ON emp.department_id = dep.id
                        JOIN jops AS jop ON emp.jop_id = jop.id
                        WHERE emp.id = $emp_id");
use Dompdf\Dompdf;
$obj = new I18N_Arabic_Glyphs();
$dompdf = new Dompdf();
foreach($emp as $em){
    $name=$em['name'];
    $divinity_no=$em['divinity_no'];
    $salary=$em['basic_salary'];
    $birthday=$em['birthday'];
    $startDate = $em['start_date'];
    $phone=$em['phone'];
    $address=$em['address'];
    $sex=$em['sex'] == true ? 'ذكر' : 'أنثى';
    $dep=$em['dep_name'];
    $jop=$em['jop_name'];
    $now=date_format(date_create(),'Y-m-d');
}


?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير أو شهادة</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/bootstrap.css">
   
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;  
        }
        .pdf-paper {
            background: white;
            padding: 20px;
            border: 1px solid #dee2e6;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            max-width: 800px;
            margin: auto;
        }
        h1, h2, h3 {
            text-align: center;
        }
        p {
            text-align: justify;
        }
    </style>
</head>
<body id="page-top">
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


<div class="pdf-paper">
    <center>
    <div class="sidebar-brand-icon ">
        
        <img src="img/Logo.png" alt="logo" srcset="" width="60px" height="60px">
        <h3>مدارس الابداع الإهلية</h3>
    </div>
    </center>
    <h1>____________________________________________</h1>
    <h1>شهادة خبرة</h1>
    <h3>تاريخ الإصدار: <?=date_format(date_create(),'Y-m-d')?></h3>
    <hr>
    <p>تشهد إدارة مدارس الإبداع بأن  <strong><?=$name?></strong> قد عمل في لديها في وظيفة <strong><?=$jop?></strong>  خلال الفترة من <strong><?=$startDate?></strong> إلى <strong><?= date_format(date_create(),'Y-m-d')?></strong>.</p>
    
    <p>وقد كان خلال فترة عمله، حسن السيرة والسلوك ومتفاني  في عملة ومتعاون مع زملائه في العمل وقد اعطيت له هده الشهاده بناء على طلبه دون تحمل أي مسؤولية على المدرسة </p>
    
    <p>نحن نشكر <strong><?=$name?></strong> على جهوده ونتمنى له المزيد من النجاح في مستقبله المهني.</p>
    
    <h3>التوقيع</h3>
    <p><strong>مدير المدرسة</strong></p>
</div>
<div class="d-flex justify-content-center my-3">
    <button class="btn btn-success mx-3">طباعة الشهادة</button>
    <button class="btn btn-outline-secondary">تحميل الشهادة</button>
</div>

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