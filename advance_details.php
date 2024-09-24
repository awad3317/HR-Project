<?php 
include('DB/database.php');
include('DB/employee.php');
include('DB/department.php');
include('DB/advance.php');

$database = new Database();
$db = $database->connect();
include("check_session.php");
if(!isset($_GET['id'])){
    header("location: advance.php");
}
$emp_id=$_GET['id'];
$advance=new advance($db);
$advances=$advance->select("SELECT 
    a.id AS advance_id,
    a.amount AS total_advance,
    a.date AS advance_date,
    COALESCE(SUM(p.amount), 0) AS total_paid,
    (a.amount - COALESCE(SUM(p.amount), 0)) AS remaining_balance
FROM 
    advances a
LEFT JOIN 
    payments p ON a.id = p.advance_id
WHERE 
    a.employee_id =$emp_id
GROUP BY 
    a.id, a.amount
ORDER BY 
    a.id;");
$total=$advance->select("SELECT SUM(amount) AS total FROM advances WHERE employee_id=$emp_id");
$employee=new employee($db);
$employees=$employee->select("SELECT employees.* ,departments.name AS 'dep_name' FROM employees JOIN departments ON  (departments.id = employees.department_id and employees.id=$emp_id)");
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
                    <ul class="breadcrumb m-3">
                            <li class="breadcrumb-item"> <a href="home.php" class='text-success'>الرئيسية</a></li> 
                            <li class="breadcrumb-item "><a href="advance.php" class='text-success'>السلف</a> </li>
                            <li class="breadcrumb-item active">عرض تفاصيل السلفة   </li> 
                         </ul>
                    <div>
                        <?php foreach($employees as $emp){} ?>
                        <h3 class="text-center"><?=$emp['name']?></h3>
                        <div class="row mb-4 justify-content-center">
                            <div class="col-md-3 text-center">
                                <img id="profileImage" src="<?=$emp['imge']?>" alt="صورة الموظف" class="img-thumbnail" width="200" height="200">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 custom-col">
                                        <p><strong>رقم الهوية:</strong> <?=$emp['divinity_no']?></p>
                                    </div>
                                    <div class="col-md-6 custom-col">
                                        <p><strong>رقم التواصل:</strong> <?=$emp['phone']?> </p>
                                    </div>
                                    <div class="col-md-6 custom-col">
                                        <p><strong>الراتب الأساسي:</strong> <?=$emp['basic_salary']?> </p>
                                    </div>
                                    <div class="col-md-6 custom-col">
                                        <p><strong>القسم :</strong> <?=$emp['dep_name']?> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4 justify-content-center">
                        <h3 class="text-center">جميع السلف</h3>
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-bordered border-bottom-success"width="100%">
                                    <thead>
                                        <tr>
                                            <th class=" bg-gray-300">#</th>
                                            <th class=" bg-gray-300">التاريخ </th>
                                            <th class=" bg-gray-300">المبلغ</th>
                                            <th class=" bg-gray-300"> المدفوعات</th>
                                            <th class=" bg-gray-300"> المتبقي</th>
                                            <th class=" bg-gray-300">الإجراءات </th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count=0; foreach($advances as $adv) {
                                           $count++; ?>
                                        <tr>
                                            <th><?=$count?></th>
                                            <th><?=$adv['advance_date']?></th>
                                            <th><?=$adv['total_advance']?></th>
                                            <th><?=$adv['total_paid']?></th>
                                            <th><?=$adv['remaining_balance']?></th>
                                            <?php if($adv['total_paid'] != $adv['total_advance']){ ?>
                                            <th><a href="payment.php?id=<?=$adv['advance_id']?>"><button class="btn btn-outline-secondary btn-sm" id="add-payment-btn"> تسديد</button></a></th>
                                            <?php } else{?>
                                                <th><button class="btn btn-success btn-sm" id="add-payment-btn"> تم السداد</button></th>
                                                <?php }?>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                    <tfoot>
                                    <?php foreach($total as $tot) {?>
                                        <tr>
                                            <th >الاجمالي</th>
                                            <th colspan="3"><?=$tot['total']?></th>
                                        </tr>
                                        <?php }?>
                                    </tfoot>
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