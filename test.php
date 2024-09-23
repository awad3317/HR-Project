<?php
require 'vendor/ar-php/ar-php/I18N/Arabic/Glyphs.php';
require 'vendor/autoload.php';
include('DB/database.php');
include('DB/employee.php');
$database = new Database();
$db = $database->connect();
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
    $startDate = new DateTime($em['start_date']);
    $today = new DateTime();
    $interval = $startDate->diff($today);
    $years = $interval->y;
    $months = $interval->m;
    $days = $interval->d;
    $phone=$em['phone'];
    $address=$em['address'];
    $sex=$em['sex'] == true ? 'ذكر' : 'أنثى';
    $dep=$em['dep_name'];
    $jop=$em['jop_name'];
}
$html = '
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            text-align: right; /* لجعل النص يظهر من اليمين إلى اليسار */
            direction: rtl; 
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: right;
        }
        th {
            background-color: #f2f2f2;
        }
        .number {
            direction: ltr; /* لإظهار الأرقام من اليسار إلى اليمين */
            text-align: left; /* محاذاة الأرقام إلى اليسار */
        }
        
    </style>
</head>
<body>
   <h1>مدارس الابداع </h1>
    <h1>بيانات الموظف</h1>
    <table>
        <tr>
            <td>' . htmlspecialchars($name) . '</td>
            <th>الاسم</th>
            <td>' . htmlspecialchars($divinity_no) . '</td>
            <th>رقم الهوية</th>
        </tr>
            
        <tr>
            <td>' . htmlspecialchars($birthday) . '</td>
            <th>تاريخ الميلاد</th>
            <td>'. htmlspecialchars($years).'  سنوات و  '.htmlspecialchars($months) .' اشهر و '.htmlspecialchars($days) .' ايام</td>
            <th>فترة العمل</th>      
        </tr>
        <tr>
            <td>' . htmlspecialchars($phone) . '</td>
            <th>رقم التواصل</th>
            <td>' . htmlspecialchars($address) . '</td>
            <th>العنوان</th>
        </tr>
        <tr>
            <td >' . htmlspecialchars($salary) . '</td>
            <th>الراتب الأساسي</th>
             <td>' . htmlspecialchars($sex) . '</td>
            <th>الجنس</th>
        </tr>
        <tr>
        <td>' . htmlspecialchars($dep) . '</td>
            <th>القسم</th>
            <td>' . htmlspecialchars($jop) . '</td>
            <th>الوظيفة</th> 
        </tr>
    </table>
</body>
</html>
';

// تحميل HTML إلى Dompdf
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("document.pdf", array("Attachment" => true));
?>