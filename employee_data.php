<?php
require 'vendor/autoload.php';
include('DB/database.php');
include('DB/employee.php');
use \PhpOffice\PhpWord\TemplateProcessor;
$database = new Database();
$db = $database->connect();
include("check_session.php");
$employee=new employee($db);
if(!isset($_GET['id'])){
    header("locaton: Reports.php");
}
$emp_id=$_GET['id'];
$emp = $employee->select("SELECT emp.*, dep.name AS dep_name, jop.name AS jop_name
                        FROM employees AS emp 
                        JOIN departments AS dep ON emp.department_id = dep.id
                        JOIN jops AS jop ON emp.jop_id = jop.id
                        WHERE emp.id = $emp_id");
$emp_allowance=$employee->select("SELECT allowance_employee.amount AS amount, allowances.name AS allowance_name FROM allowance_employee 
JOIN allowances ON allowance_employee.allowance_id = allowances.id 
WHERE allowance_employee.employee_id=$emp_id");
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
$count=0;
if(isset($_GET['save'])){
    $TemplateProcessor = new TemplateProcessor('employee_data.docx');
    $TemplateProcessor->setValue('name',$name);
    $TemplateProcessor->setValue('salary',$salary);
    $TemplateProcessor->setValue('jop',$jop);
    $TemplateProcessor->setValue('phone',$phone);
    $TemplateProcessor->setValue('birthdate',$birthday);
    $TemplateProcessor->setValue('divinity_no',$divinity_no);
    $TemplateProcessor->setValue('department',$dep);
    $TemplateProcessor->setValue('address',$address);
    foreach($emp_allowance as $allowance){
        $count++;
        $TemplateProcessor->setValue('type'.$count,$allowance['allowance_name']);
        $TemplateProcessor->setValue('allownce'.$count,$allowance['amount']);
    }
    

    $filePath = 'Certificate/' . $name . 'employee_data.docx';
    $TemplateProcessor->saveAs($filePath);
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
    unlink($filePath);
    exit;
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
            padding: 50px;
            border: 1px solid #dee2e6;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            max-width: 800px;
            margin: auto;
            min-height: 842px;
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
        <img src="img/Logo.png" alt="logo" srcset="" width="70px" height="70px">
        <h3>مدارس الابداع الإهلية</h3>
    </div>
    </center>
    <h1>-------------------------------------------</h1>
    <h1>تقرير موظف</h1>
    <br>
    <center> <h5>بيانات الموظف الاساسية</h5></center>
    <table class="table table-bordered">
        <tr>
            <th>الاسم :</th>
            <th><?=$name?></th>
        </tr>
        <tr>
            <th>الراتب:</th>
            <th><?=$salary?></th>
        </tr>
        <tr>
            <th>رقم التواصل :</th>
            <th><?=$phone?></th>
        </tr>
        <tr>
            <th>تاريخ الميلاد:</th>
            <th><?=$birthday?></th>
        </tr>
        <tr>
            <th>رقم الهوية :</th>
            <th><?=$divinity_no?></th>
        </tr>
        <tr>
            <th>القسم : </th>
            <th><?=$dep?></th>
        </tr>
        <tr>
            <th>الوظيفة:</th>
            <th><?=$address?></th>
        </tr>
        <tr>
            <th>العنوان:</th>
            <th><?=$phone?></th>
        </tr>
    </table>

    <center><h5>بدلات الموظف</h5></center>
    <table class="table table-bordered">
        <tr>
       <?php foreach($emp_allowance as $allowance){?>
            <th><?=$allowance['allowance_name']?></th>  
        <?php }?>
        <th>أخرى</th>
        </tr>
        <tr>
        <?php foreach($emp_allowance as $allowance){?>
            <th><?=$allowance['amount']?></th>  
        <?php }?>
            <th></th>
        </tr>
    </table>
</div>
<div class="d-flex justify-content-center my-3">
    <a class="btn btn-success mx-3" href="employee_data.php?id=<?=$emp_id?>&save=true">تحميل التقرير</a>
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