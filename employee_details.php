<?php 
include('DB/database.php');
include('DB/employee.php');
include('DB/allowance.php');
include('DB/advance.php');
include('DB/file.php');
include('DB/jop.php');
include('DB/leave.php');
include('DB/department.php');
include('DB/employee_file.php');
include('DB/allowance_employee.php');
include('Validattion/Validator.php');

session_start();

$database = new Database();
$db = $database->connect();

if(!isset($_GET['id']) and !isset($_POST['save']) and !isset($_POST['ok']) and !isset($_POST['save_file'])){
    header("location: Employee.php");
}
if(isset($_POST['ok'])){
    $data=[
        'amount'=>$_POST['amount'],
        'date'=>date_format(date_create(),'Y-m-d'),
        'employee_id'=>$_POST['emp_id']
    ];
    $advance= new advance($db);
    $advance->Create($data);
    $emp_id=$_POST['emp_id'];
}

if(isset($_POST['save_file'])){
    $path='Upload/'.random_int(999,99999).$_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'],$path);
    $data=[
        'type1'=>$_POST['type'],
        'path1'=>$path,
        'employee_id'=>$_POST['emp_id']
    ];
    $employee_file= new employee_file($db);
    $employee_file->Create($data);
}

if(isset($_POST['save'])){
    $data=[
        'type1'=>$_POST['type'],
        'allowance1'=>$_POST['amount'],
        'employee_id'=>$_POST['emp_id']
    ];
    $allowance_employee= new allowance_employee($db);
    $allowance_employee->Create($data);
    $emp_id=$_POST['emp_id'];
}
if(isset($_GET['id'])){
    $emp_id=$_GET['id'];
}

//Get the employee
$employee= new employee($db);
$emp = $employee->select("SELECT emp.*, dep.name AS dep_name, jop.name AS jop_name
                        FROM employees AS emp 
                        JOIN departments AS dep ON emp.department_id = dep.id
                        JOIN jops AS jop ON emp.jop_id = jop.id
                        WHERE emp.id = $emp_id");

$emp_allowance=$employee->select("SELECT allowance_employee.amount AS amount, allowances.name AS allowance_name FROM allowance_employee 
                                JOIN allowances ON allowance_employee.allowance_id = allowances.id 
                                WHERE allowance_employee.employee_id=$emp_id");
$emp_file=$employee->select("SELECT path,type FROM employee_file AS emp_file JOIN file ON emp_file.file_id = file.id WHERE emp_file.employee_id = $emp_id ");
$emp_advance=$employee->select("SELECT * FROM advances WHERE employee_id = $emp_id");
$allowance= new allowance($db);
$allowances=$allowance->All();
$file_type= new file($db);
$file_types=$file_type->All();
$leave=new leave($db);
$leaves=$leave->select("SELECT * FROM leaves JOIN leave_type ON leaves.leave_type_id = leave_type.id WHERE leaves.employee_id= $emp_id")
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
                                <button class="btn btn-outline-secondary btn-sm" id="add-allowance-btn">إضافة بدل</button>
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
                                <button class="btn btn-outline-secondary btn-sm" id="add-file-btn">إضافة مرفق</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered border-bottom-success" width="100%">
                                    <tbody>
                                        <?php foreach($emp_file as $file) { ?>
                                        <tr>
                                            <th class="text-center bg-gray-200"><?=$file['type']?></th>
                                            <th class="text-center">
                                                <a href="<?=$file['path']?>" target="_blank" class="btn btn-outline-secondary btn-md" download="مرفق">تحميل</a>
                                                <a href="<?=$file['path']?>" target="_blank" class="btn btn-outline-success btn-md">فتح</a>
                                            </th> 
                                        </tr>
                                        <?php } ?>
                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h3 class="text-center">سلفات الموظف</h3>
                                <button class="btn btn-outline-secondary btn-sm" id="add-advance-btn">طلب سلفه جديده</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered border-bottom-success" width="100%">
                                    <tbody>
                                        <?php $total=0; foreach($emp_advance as $advance) { ?>
                                        <tr>
                                            <th class="text-center bg-gray-200"><?=$advance['date']?></th>
                                            <th class="text-center"><?=$advance['amount']?></th> 
                                        </tr>
                                        <?php $total+=$advance['amount']; } ?>
                                        <tr>
                                            <th class="text-center bg-gray-200">الاجمالي</th>
                                            <th class="text-center"><?=$total?></th> 
                                        </tr>
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h3 class="text-center">إجازات الموظف</h3>
                                <button class="btn btn-outline-secondary btn-sm">طلب إجازة جديده</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered border-bottom-success" width="100%">
                                    <thead>
                                        <tr>
                                            <th>السبب</th>
                                            <th>البدء</th>
                                            <th>النهاية</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $total=0; foreach($leaves as $leave) { ?>
                                        <tr>
                                            <th class="text-center bg-gray-200"><?=$leave['type']?></th>
                                            <th class="text-center"><?=$leave['start']?></th> 
                                            <th class="text-center"><?=$leave['end']?></th> 
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

   <script>
    document.getElementById('add-allowance-btn').addEventListener('click', async () => {
    const allowancesOptions = `
        <?php foreach ($allowances as $allowance) { ?>
            <option value="<?= $allowance['id'] ?>"><?= $allowance['name'] ?></option>
        <?php } ?>
    `;

    const { value: formValues } = await Swal.fire({
        title: 'إضافة بدل',
        html: `
            <select id="allowance-select" class="form-control mb-2">
                <option value="">اختر نوع البدل</option>
                ${allowancesOptions}
            </select>
            <input id="amount-input" type="text" class="form-control" placeholder="المبلغ">
        `,
        focusConfirm: false,
        preConfirm: () => {
            const allowanceId = document.getElementById("allowance-select").value;
            const amountValue = document.getElementById("amount-input").value;
            if (!allowanceId || !amountValue) {
                Swal.showValidationMessage('يرجى إدخال جميع الحقول');
                return false;
            }
            return [allowanceId, amountValue];
        },
        confirmButtonText: 'إضافة', 
        cancelButtonText: 'إلغاء',
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-secondary',
            title: ' text-success'
        },
        showCancelButton: true,
    });

    if (formValues) {
        const [allowanceId, amountValue] = formValues;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href; 

        const allowanceInput = document.createElement('input');
        allowanceInput.type = 'hidden';
        allowanceInput.name = 'type';
        allowanceInput.value = allowanceId; // استخدم allowanceId هنا
        form.appendChild(allowanceInput);

        const amountInput = document.createElement('input');
        amountInput.type = 'hidden';
        amountInput.name = 'amount';
        amountInput.value = amountValue; // استخدم amountValue هنا
        form.appendChild(amountInput);

        const empIdInput = document.createElement('input');
        empIdInput.type = 'hidden';
        empIdInput.name = 'emp_id';
        empIdInput.value = <?=$emp_id?>;
        form.appendChild(empIdInput);

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
<script>
       document.getElementById('add-advance-btn').addEventListener('click', async () => {
    const { value: amount_value } = await Swal.fire({
        title: ' طلب سلفه جديد',
        html: `
           <div class="d-flex justify-content-between align-items-center">
                <h5 class="m-0">مقدار السلفه  </h5>
            </div>
            <input id="swal-input-advance" class="form-control mt-2" placeholder="المقدار">
        `,
        focusConfirm: false, 
        preConfirm: () => {
            const amount = document.getElementById("swal-input-advance").value;
            if (!amount) {
                Swal.showValidationMessage('يرجى إدخال مقدار السلفه');
                return false;
        }
            return amount;
        },
        confirmButtonText: 'تأكيد', 
        cancelButtonText: 'إلغاء',
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton:  'btn btn-secondary',
            title: ' text-success'
            
        },
        showCancelButton: true,
        
        
    });

    if (amount_value) {
       
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href; 

        const advance_amount = document.createElement('input');
        advance_amount.type = 'hidden';
        advance_amount.name = 'amount';
        advance_amount.value = amount_value;
        form.appendChild(advance_amount);

        const empIdInput = document.createElement('input');
        empIdInput.type = 'hidden';
        empIdInput.name = 'emp_id';
        empIdInput.value = <?=$emp_id?>;
        form.appendChild(empIdInput);

        const inputSave = document.createElement('input');
        inputSave.type = 'hidden';
        inputSave.name = 'ok';
        inputSave.value = 'true';
        form.appendChild(inputSave);

        document.body.appendChild(form);
        form.submit();
    }
});
    </script>
<script>
       document.getElementById('add-file-btn').addEventListener('click', async () => {
        const typesOptions = `
        <?php foreach ($file_types as $type) { ?>
            <option value="<?= $type['id'] ?>"><?= $type['type'] ?></option>
        <?php } ?>
    `;
    const { value: formValues } = await Swal.fire({
        title: 'إضافة مرفق',
        html: `
            <select id="type-select" class="form-control mb-2">
                <option value="">اختر نوع المرفق</option>
                ${typesOptions}
            </select>
            <input id="file" type="file" class="form-control" placeholder="">
        `,
        focusConfirm: false,
        preConfirm: () => {
            const type = document.getElementById("type-select").value;
            const file = document.getElementById("file").value;
            if (!type || !file) {
                Swal.showValidationMessage('يرجى إدخال جميع الحقول ');
                return false;
        }
            return [type, file];
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

    if (formValues) {
        const [type, file] = formValues;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href; 

        const type_file = document.createElement('input');
        type_file.type = 'hidden';
        type_file.name = 'type';
        type_file.value = type;
        form.appendChild(type_file);

        const file_path = document.createElement('input');
        file_path.type = 'hidden';
        file_path.name = 'file';
        file_path.value = file;
        form.appendChild(file_path);

        const emp_id = document.createElement('input');
        emp_id.type = 'hidden';
        emp_id.name = 'emp_id';
        emp_id.value = <?=$emp_id?>;
        form.appendChild(emp_id);

        const inputSave = document.createElement('input');
        inputSave.type = 'hidden';
        inputSave.name = 'save_file';
        inputSave.value = 'true';
        form.appendChild(inputSave);

        document.body.appendChild(form);
        form.submit();
    }
});
    </script>
</body>
</html>