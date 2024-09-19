<?php
include('DB/database.php');
include('DB/jop.php');
include('DB/advance.php');
include('Validattion/Validator.php');
include('DB/employee.php');

$database = new Database();
$db = $database->connect();
$advance=new advance($db);
$employee=new employee($db);
$employees=$employee->All();
if (isset($_POST['save'])) {
    $data=[
        'amount'=>$_POST['amount'],
        'date'=>date_format(date_create(),'Y-m-d'),
        'employee_id'=>$_POST['employee_id'],

    ];
    $advance_id=$advance->Create($data);
}

$results=$advance->select("SELECT sum(amount) AS 'total',departments.name AS 'dep_name',employees.id AS 'emp_id', employees.name AS 'emp_name', employees.phone AS 'emp_phone' FROM advances JOIN employees ON advances.employee_id= employees.id JOIN departments ON employees.department_id = departments.id group by advances.employee_id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>إضافة سلفة</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link href="css/sb-admin-2.css" rel="stylesheet">
</head>

<body id="page-top">

<div id="wrapper">
    <?php include('Sidebar.html') ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('navbar.html') ?>
            <div class="container-fluid">
                <ul class="breadcrumb m-3">
                    <li class="breadcrumb-item"><a href="home.php">الرئيسية</a></li>
                    <li class="breadcrumb-item active"> السلف </li>
                </ul>
               
                <h1>إضافة سلفة جديدة</h1>
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">قيمة السلفة: <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="amount" name="amount" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="employee_id"> الموظف: <span class="text-danger">*</span></label>
                                <select class="form-control" id="employee_id" name="employee_id" required>
                                    <option value="">اختر الموظف</option>
                                    <?php foreach ($employees as $employee){ ?>
                                        <option value="<?= htmlspecialchars($employee['id']) ?>"><?= htmlspecialchars($employee['name']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="save" class="btn btn-primary">إضافة سلفة </button>
                </form>

                <h2 class="mt-5">جدول السلف</h2>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الموظف</th>
                        <th>الاجمالي</th>
                        <th>رقم التواصل</th>
                        <th>القسم </th>
                        <th>التفاصيل</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count = 0; foreach ($results as $result): $count++; ?>
                        <tr>
                            <td><?= $count ?></td>
                            <td><?= htmlspecialchars($result['emp_name']) ?></td>
                            <td><?= htmlspecialchars($result['total']) ?></td>
                            <td><?= htmlspecialchars($result['emp_phone']) ?></td>
                            <td><?= htmlspecialchars($result['dep_name']) ?></td>
                            <td><a href="advance_details.php?id=<?= $result['emp_id']?>"><button class="btn btn-outline-primary">التفاصيل</button></a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php include("footer.html") ?>
        </div>
    </div>
</div>

<!-- Scroll to Top Button-->
<?php include("Scroll.html") ?>

<!-- Logout Modal-->
<?php include("Logout_model.html") ?>

<!-- Bootstrap core JavaScript-->
<?php include("script.html") ?>

</body>
</html>
