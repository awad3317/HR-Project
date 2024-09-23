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
    <title>شهادة خبرة</title>
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
    <h1>شهادة خبرة</h1>
    <hr>
    <p>تشهد إدارة مدارس الإبداع الأهلية بالقطن بأن:</p>
    <table>
        <tr>
            <th>اسم الموظف</th>
            <td>أدخل اسم الموظف هنا</td>
        </tr>
        <tr>
            <th>الوظيفة</th>
            <td>أدخل الوظيفة هنا</td>
        </tr>
        <tr>
            <th>من سنة</th>
            <td class="number">أدخل سنة البداية هنا</td>
        </tr>
        <tr>
            <th>إلى سنة</th>
            <td class="number">أدخل سنة النهاية هنا</td>
        </tr>
    </table>
    <p>وبهذا، نؤكد أن الموظف المذكور أعلاه قد عمل لدينا خلال الفترة المحددة.</p>
    <p>تاريخ: ____________</p>
    <p>التوقيع: ____________</p>
</body>
</html>
';

// تحميل HTML إلى Dompdf
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("document.pdf", array("Attachment" => true));
?>