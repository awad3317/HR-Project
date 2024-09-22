<?php 
include('DB/database.php');
include('DB/bonus.php');
include('DB/employee.php');

unset($_SESSION['data_basic']);
unset($_SESSION['allowances']);
$bo=false;
$database = new Database();
$db = $database->connect();
$bonus= new bonus($db);
$employee=new employee($db);
include("check_session.php");
if(isset($_POST['save'])){
    $data=[
        'amount'=>$_POST['amount'],
        'date'=>date_format(date_create(),"Y-m-d"),
        'employee_id'=>$_POST['emp']
    ];
    $bonus->Create($data);

}

if(isset($_POST['emp_id'])){
    $emp_id=$_POST['emp_id'];
    $bonu=$bonus->select("SELECT employees.name,bonus.amount,bonus.date FROM employees JOIN bonus ON employees.id = bonus.employee_id WHERE employees.id=$emp_id");
    $bo=true;
}

$employees=$employee->All();

$employee_bonus=$employee->select("SELECT employees.name AS name, employees.id AS emp_id, bonus.date AS 'date',bonus.id AS bonus_id, sum(amount) AS total FROM employees JOIN bonus ON employees.id = bonus.employee_id Group by employees.name");
$count=0;
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Tables</title>

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

                    <ul  class="breadcrumb m-3">
                        <li class="breadcrumb-item"> <a href="home.php" class='text-success'>الرئيسية</a></li> 
                        <li class="breadcrumb-item active">العلاوات  </li> 
                    </ul>
                    
                    <button id="add-bonus-btn" class="btn btn-outline-success">إعطاء علاوه</button>
                    <h3 class="mb-2 mt-3 text-gray-800">العلاوات</h3>
                    
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="bg-gradient-success text-gray-100">#</th>
                                            <th class="bg-gradient-success text-gray-100">الموظف</th>
                                            <th class="bg-gradient-success text-gray-100">إجمالي مقدار العلاوات</th>
                                            <th class="bg-gradient-success text-gray-100">التفاصيل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($employee_bonus as $emp){ 
                                         $count++; ?>
                                        <tr>
                                            <td><?=$count?></td>
                                            <td><?=htmlspecialchars($emp['name'])?></td>
                                            <td><?=htmlspecialchars($emp['total'])?></td>
                                            <td><form action="" method="post"> <input type="hidden" name="emp_id" value="<?=$emp['emp_id']?>"> <input class="btn btn-outline-secondary" type="submit" value="التفاصيل"> </form></td>
                                        </tr>
                                        <?php }?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->

        </div>
    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <?php include("footer.html") ?>
    <!-- End of Footer -->

    <!-- Scroll to Top Button-->
    <?php include("Scroll.html") ?>

    <!-- Logout Modal-->
    <?php include("Logout_model.html") ?>

    <!-- Bootstrap core JavaScript-->
    <?php include("script.html") ?>

    <script>
       document.getElementById('add-bonus-btn').addEventListener('click', async () => {
        const empOptions = `
        <?php foreach ($employees as $emp) { ?>
            <option value="<?= $emp['id'] ?>"><?= $emp['name'] ?></option>
        <?php } ?>
    `;
    const { value: bonus } = await Swal.fire({
        title: 'إعطاء علاوه جديده',
        html: `
            <select id="emp-select" class="form-control mb-2">
                <option value="">اختر  الموظف</option>
                ${empOptions}
            </select>
           <div class="d-flex justify-content-between align-items-center">
                <input id="swal-input" class="form-control mt-2" placeholder="المقدار ">
            </div>
            
        `,
        focusConfirm: false, 
        preConfirm: () => {
            const emp = document.getElementById("emp-select").value;
            const amount = document.getElementById("swal-input").value;
            if (!emp || !amount) {
                Swal.showValidationMessage('يرجى إدخال جميع الحقول');
                return false;
        }
            return [emp,amount];
        },
        confirmButtonText: 'إضافة', 
        cancelButtonText: 'إلغاء',
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton:  'btn btn-secondary',
            title: ' text-success'
        },
        showCancelButton: true,
    });

    if (bonus) {
        const [emp, amount] = bonus;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href; 

        const inputemp = document.createElement('input');
        inputemp.type = 'hidden';
        inputemp.name = 'emp';
        inputemp.value = emp;
        form.appendChild(inputemp);

        const inputamount = document.createElement('input');
        inputamount.type = 'hidden';
        inputamount.name = 'amount';
        inputamount.value = amount;
        form.appendChild(inputamount);

        const inputSave = document.createElement('input');
        inputSave.type = 'hidden';
        inputSave.name = 'save';
        inputSave.value = 'true';
        form.appendChild(inputSave);

        document.body.appendChild(form);
        form.submit();
    }
});
    </script>
     <?php if (isset($message)): ?>
        <script>
             Swal.fire({
            title: '<?php echo isset($validationErrors) ? "فشل الإضافة!" : "تم الإضافة!"; ?>',
            text: '<?php echo isset($message) ? $message : ""; ?>',
            icon: '<?php echo isset($validationErrors) ? "error" : "success"; ?>',
            timer: 2000, 
            showConfirmButton: false
        });
        </script>
    <?php endif; ?>
    <?php if (isset($bo) and $bo==true ): ?>
    <script>
     Swal.fire({
        title: ' <?php foreach($bonu as $bon) { echo htmlspecialchars($bon['name']); break; } ?> ',
        html: `
            <div class="card shadow mb-4">  
                                <div class="card-body">
                                    <div class="table-responsive" style="max-height: 200px; min-height: 200px; overflow-y: auto;">
                                        <table class="table table-bordered border-bottom-success" width="100%">
                                            <thead>
                                                <tr>
                                                    <th class="text-center bg-gray-200">التاريخ</th>
                                                    <th class="text-center bg-gray-200">المقدار</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php  foreach($bonu as $bon) { ?>
                                                <tr>
                                                    <th class="text-center"><?=htmlspecialchars($bon['date'])?></th>
                                                    <th class="text-center"><?=htmlspecialchars($bon['amount'])?></th>
                                                    
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
        `,
        focusConfirm: false, 
        customClass: {
            
        },
        
    });
    </script>
    <?php endif; ?>



</body>

</html>